import Vapor

/// Gestor de Notificaciones (Production Ready for Koyeb)
/// - Lee la configuración del entorno
/// - Robusto ante cambios de URL en la nube
public class NotificationManager {
    
    // MARK: - Singleton
    public static let shared = NotificationManager()
    
    // MARK: - Configuración Dinámica
    // En producción, esto leerá la variable de entorno. En local, usa localhost.
    private var webSocketServiceURL: String {
        // Intentamos leer la variable 'NOTIFICATION_SERVICE_URL' del sistema
        let baseURL = Environment.get("NOTIFICATION_SERVICE_URL") ?? "http://localhost:8080"
        return "\(baseURL)/api/v1/notifications/send"
    }
    
    private init() {}
    
    // MARK: - Métodos Principales
    
    public func notifyUser(app: Application, userID: String, title: String, message: String, type: String = "info") async throws {
        try await sendToGoEngine(app: app, userIDs: [userID], title: title, message: message, type: type)
    }
    
    public func notifyMultipleUsers(app: Application, userIDs: [String], title: String, message: String, type: String = "info") async throws {
        try await sendToGoEngine(app: app, userIDs: userIDs, title: title, message: message, type: type)
    }
    
    public func notifyAll(app: Application, title: String, message: String) async throws {
        let payload = NotificationPayload(
            type: "global_alert",
            title: title,
            message: message,
            user_id: "system",
            channels: ["global_alerts"]
        )
        try await dispatchRequest(app: app, payload: payload)
    }
    
    // MARK: - Lógica de Negocio
    
    public func handleTareaNotification(app: Application, userID: String, tareaTitulo: String) async throws {
        let titulo = "📝 Tarea Pendiente"
        let mensaje = "No olvides completar: \(tareaTitulo)"
        try await notifyUser(app: app, userID: userID, title: titulo, message: mensaje, type: "tarea")
    }
    
    public func handleFinanzasNotification(app: Application, userIDs: [String], monto: Double) async throws {
        let titulo = "💰 Alerta Financiera"
        let mensaje = "Se ha registrado un movimiento de $\(monto)"
        try await notifyMultipleUsers(app: app, userIDs: userIDs, title: titulo, message: mensaje, type: "finanzas")
    }
    
    public func handleHabitoNotification(app: Application, userID: String, habito: String) async throws {
        try await notifyUser(app: app, userID: userID, title: "🌱 Hábito", message: "Es hora de: \(habito)", type: "habito")
    }
    
    // MARK: - Motor de Envío (Conexión con Go)
    
    private func sendToGoEngine(app: Application, userIDs: [String], title: String, message: String, type: String) async throws {
        for id in userIDs {
            let payload = NotificationPayload(
                type: type,
                title: title,
                message: message,
                user_id: id,
                channels: ["user_" + id]
            )
            try await dispatchRequest(app: app, payload: payload)
        }
    }
    
    private func dispatchRequest(app: Application, payload: NotificationPayload) async throws {
        // Log para depuración en Koyeb (se verá en la consola web)
        app.logger.info("🚀 SWIFT -> GO: Enviando a \(self.webSocketServiceURL) para Usuario: \(payload.user_id)")
        
        // Realizamos la petición usando la URL dinámica
        let response = try await app.client.post(URI(string: self.webSocketServiceURL)) { req in
            try req.content.encode(payload)
        }
        
        if response.status != .ok {
            app.logger.error("❌ Error enviando a Go [\(response.status)]: Verifica que la URL del servicio Go sea correcta.")
        }
    }
}

// MARK: - Estructuras de Datos
struct NotificationPayload: Content {
    var type: String
    var title: String
    var message: String
    var user_id: String
    var channels: [String]
}