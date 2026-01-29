package main

import (
	"context"
	"fmt"
	"log"
	"net/http"
	"os"
	"sync"
	"time"

	"github.com/gin-gonic/gin"
	"github.com/google/uuid"
	"github.com/gorilla/websocket"
	"github.com/redis/go-redis/v9"
	"go.uber.org/zap"
)

// ======================================================
// CONFIGURACIÓN GLOBAL
// ======================================================

var (
	hub = &Hub{
		clients:    make(map[*Client]bool),
		broadcast:  make(chan *Notification, 256),
		register:   make(chan *Client),
		unregister: make(chan *Client),
	}

	redisClient *redis.Client
	logger      *zap.Logger
	mu          sync.RWMutex
)

// ======================================================
// ESTRUCTURAS DE DATOS
// ======================================================

type Hub struct {
	clients    map[*Client]bool
	broadcast  chan *Notification
	register   chan *Client
	unregister chan *Client
	mu         sync.RWMutex
}

// CORRECCIÓN: Estructura Client con todos los campos necesarios
type Client struct {
	hub      *Hub
	conn     *websocket.Conn
	send     chan *Notification
	id       string
	userID   string
	channels []string // Campo necesario para evitar el error "undefined"
}

type Notification struct {
	ID        string                 `json:"id"`
	Type      string                 `json:"type"`
	Title     string                 `json:"title"`
	Message   string                 `json:"message"`
	Data      map[string]interface{} `json:"data,omitempty"`
	UserID    string                 `json:"user_id"`
	Channels  []string               `json:"channels"`
	Timestamp time.Time              `json:"timestamp"`
	Read      bool                   `json:"read"`
}

type NotificationRequest struct {
	Type     string                 `json:"type" binding:"required"`
	Title    string                 `json:"title" binding:"required"`
	Message  string                 `json:"message" binding:"required"`
	Data     map[string]interface{} `json:"data"`
	UserID   string                 `json:"user_id" binding:"required"`
	Channels []string               `json:"channels"`
}

type ScheduledNotification struct {
	ID          string                 `json:"id"`
	UserID      string                 `json:"user_id"`
	Title       string                 `json:"title"`
	Message     string                 `json:"message"`
	Type        string                 `json:"type"`
	Data        map[string]interface{} `json:"data"`
	ScheduledAt time.Time              `json:"scheduled_at"`
	CreatedAt   time.Time              `json:"created_at"`
}

// ======================================================
// HUB WEBSOCKET
// ======================================================

func (h *Hub) run() {
	for {
		select {
		case client := <-h.register:
			h.mu.Lock()
			h.clients[client] = true
			h.mu.Unlock()
			logger.Info("Client registered", zap.String("client_id", client.id))

		case client := <-h.unregister:
			h.mu.Lock()
			if _, ok := h.clients[client]; ok {
				delete(h.clients, client)
				close(client.send)
			}
			h.mu.Unlock()
			logger.Info("Client unregistered", zap.String("client_id", client.id))

		case message := <-h.broadcast:
			h.mu.RLock()
			for client := range h.clients {
				if shouldReceive(client, message) {
					select {
					case client.send <- message:
					default:
						close(client.send)
						delete(h.clients, client)
					}
				}
			}
			h.mu.RUnlock()
			publishToRedis(message)
		}
	}
}

func shouldReceive(client *Client, notif *Notification) bool {
	if notif.UserID != "" && notif.UserID != client.userID {
		return false
	}
	if len(notif.Channels) > 0 {
		for _, channel := range notif.Channels {
			for _, clientChannel := range client.channels {
				if channel == clientChannel {
					return true
				}
			}
		}
		return false
	}
	return true
}

// ======================================================
// CLIENTE WEBSOCKET
// ======================================================

func (c *Client) readPump() {
	defer func() {
		c.hub.unregister <- c
		c.conn.Close()
	}()

	c.conn.SetReadDeadline(time.Now().Add(60 * time.Second))
	c.conn.SetPongHandler(func(string) error {
		c.conn.SetReadDeadline(time.Now().Add(60 * time.Second))
		return nil
	})

	for {
		var msg map[string]interface{}
		err := c.conn.ReadJSON(&msg)
		if err != nil {
			if websocket.IsUnexpectedCloseError(err, websocket.CloseGoingAway, websocket.CloseAbnormalClosure) {
				logger.Error("WebSocket error", zap.Error(err))
			}
			break
		}

		// CORRECCIÓN: Manejo de suscripciones
		if action, ok := msg["action"].(string); ok {
			switch action {
			case "subscribe":
				if channels, ok := msg["channels"].([]interface{}); ok {
					for _, ch := range channels {
						if channelStr, ok := ch.(string); ok {
							c.channels = append(c.channels, channelStr)
						}
					}
				}
			case "unsubscribe":
				if channels, ok := msg["channels"].([]interface{}); ok {
					for _, ch := range channels {
						if channelStr, ok := ch.(string); ok {
							// Remover canal del slice
							newChannels := c.channels[:0]
							for _, x := range c.channels {
								if x != channelStr {
									newChannels = append(newChannels, x)
								}
							}
							c.channels = newChannels
						}
					}
				}
			}
		}
	}
}

func (c *Client) writePump() {
	ticker := time.NewTicker(54 * time.Second)
	defer func() {
		ticker.Stop()
		c.conn.Close()
	}()

	for {
		select {
		case message, ok := <-c.send:
			c.conn.SetWriteDeadline(time.Now().Add(10 * time.Second))
			if !ok {
				c.conn.WriteMessage(websocket.CloseMessage, []byte{})
				return
			}
			if err := c.conn.WriteJSON(message); err != nil {
				return
			}

		case <-ticker.C:
			c.conn.SetWriteDeadline(time.Now().Add(10 * time.Second))
			if err := c.conn.WriteMessage(websocket.PingMessage, nil); err != nil {
				return
			}
		}
	}
}

// ======================================================
// HTTP HANDLERS
// ======================================================

func handleWebSocket(c *gin.Context) {
	upgrader := websocket.Upgrader{
		ReadBufferSize:  1024,
		WriteBufferSize: 1024,
		CheckOrigin: func(r *http.Request) bool {
			return true
		},
	}

	conn, err := upgrader.Upgrade(c.Writer, c.Request, nil)
	if err != nil {
		logger.Error("WebSocket upgrade failed", zap.Error(err))
		return
	}

	userID := c.Query("user_id")
	if userID == "" {
		conn.Close()
		return
	}

	client := &Client{
		hub:      hub,
		conn:     conn,
		send:     make(chan *Notification, 256),
		id:       uuid.New().String(),
		userID:   userID,
		channels: []string{}, // Inicializar slice vacío
	}

	hub.register <- client

	go client.writePump()
	go client.readPump()
}

func sendNotification(c *gin.Context) {
	var req NotificationRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	notif := &Notification{
		ID:        uuid.New().String(),
		Type:      req.Type,
		Title:     req.Title,
		Message:   req.Message,
		Data:      req.Data,
		UserID:    req.UserID,
		Channels:  req.Channels,
		Timestamp: time.Now(),
		Read:      false,
	}

	hub.broadcast <- notif
	logger.Info("Notification sent", zap.String("notif_id", notif.ID))

	c.JSON(http.StatusOK, gin.H{
		"id":        notif.ID,
		"timestamp": notif.Timestamp,
	})
}

func scheduleNotification(c *gin.Context) {
	var req ScheduledNotification
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	req.ID = uuid.New().String()
	req.CreatedAt = time.Now()

	key := fmt.Sprintf("notification:scheduled:%s", req.ID)
	err := redisClient.HSet(context.Background(), key, map[string]interface{}{
		"user_id":      req.UserID,
		"title":        req.Title,
		"message":      req.Message,
		"type":         req.Type,
		"scheduled_at": req.ScheduledAt.Unix(),
	}).Err()

	if err != nil {
		logger.Error("Failed to schedule notification", zap.Error(err))
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to schedule"})
		return
	}

	redisClient.Expire(context.Background(), key, 24*time.Hour)
	logger.Info("Notification scheduled", zap.String("notif_id", req.ID))
	c.JSON(http.StatusOK, req)
}

func getNotifications(c *gin.Context) {
	userID := c.Param("user_id")
	// CORRECCIÓN: Variable 'limit' eliminada porque no se usaba

	c.JSON(http.StatusOK, gin.H{
		"user_id":       userID,
		"notifications": []Notification{},
		"total":         0,
	})
}

func markAsRead(c *gin.Context) {
	notifID := c.Param("notification_id")
	userID := c.Param("user_id")

	logger.Info("Notification marked as read",
		zap.String("notif_id", notifID),
		zap.String("user_id", userID))

	c.JSON(http.StatusOK, gin.H{"success": true})
}

func health(c *gin.Context) {
	c.JSON(http.StatusOK, gin.H{
		"status":    "healthy",
		"service":   "notification-service",
		"version":   "1.0.0",
		"timestamp": time.Now(),
	})
}

// ======================================================
// REDIS
// ======================================================

func publishToRedis(notif *Notification) {
	ctx, cancel := context.WithTimeout(context.Background(), 5*time.Second)
	defer cancel()

	if notif.UserID != "" {
		channel := fmt.Sprintf("notifications:user:%s", notif.UserID)
		redisClient.Publish(ctx, channel, fmt.Sprintf("%s|%s", notif.ID, notif.Type))
	}

	for _, ch := range notif.Channels {
		channel := fmt.Sprintf("notifications:channel:%s", ch)
		redisClient.Publish(ctx, channel, notif.ID)
	}
}

// ======================================================
// MAIN
// ======================================================

func init() {
	var err error
	logger, err = zap.NewProduction()
	if err != nil {
		log.Fatal(err)
	}

	redisClient = redis.NewClient(&redis.Options{
		Addr: getEnv("REDIS_URL", "localhost:6379"),
	})

	ctx, cancel := context.WithTimeout(context.Background(), 5*time.Second)
	defer cancel()
	if err := redisClient.Ping(ctx).Err(); err != nil {
		logger.Warn("Redis unavailable, running in standalone mode", zap.Error(err))
	}
}

func main() {
	defer logger.Sync()

	go hub.run()

	gin.SetMode(gin.ReleaseMode)
	router := gin.New()
	router.Use(gin.Logger())
	router.Use(gin.Recovery())

	router.GET("/ws", handleWebSocket)

	api := router.Group("/api/v1/notifications")
	{
		api.POST("/send", sendNotification)
		api.POST("/schedule", scheduleNotification)
		api.GET("/user/:user_id", getNotifications)
		api.PUT("/:user_id/:notification_id/read", markAsRead)
	}
	router.GET("/health", health)

	port := getEnv("PORT", "8080")
	logger.Info("Starting notification service", zap.String("port", port))

	if err := router.Run(":" + port); err != nil {
		logger.Fatal("Server failed", zap.Error(err))
	}
}

func getEnv(key, defaultVal string) string {
	if value, exists := os.LookupEnv(key); exists {
		return value
	}
	return defaultVal
}
