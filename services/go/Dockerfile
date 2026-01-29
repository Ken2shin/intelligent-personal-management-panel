# Stage 1: Build
FROM golang:1.21-alpine AS builder

WORKDIR /app

# Instalar dependencias
RUN apk add --no-cache git

# Copiar módulos
COPY go.mod go.sum ./

# Descargar dependencias
RUN go mod download

# Copiar código
COPY . .

# Compilar
RUN CGO_ENABLED=0 GOOS=linux go build -a -installsuffix cgo -o notification-service .

# Stage 2: Runtime
FROM alpine:latest

WORKDIR /app

# Instalar CA certificates
RUN apk --no-cache add ca-certificates

# Copiar binario
COPY --from=builder /app/notification-service .

# Variables de entorno
ENV PORT=8080
ENV REDIS_URL=redis:6379
ENV GIN_MODE=release

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD wget --quiet --tries=1 --spider http://localhost:8080/health || exit 1

# Puerto
EXPOSE 8080

# Ejecutar
CMD ["./notification-service"]
