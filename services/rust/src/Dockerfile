# Stage 1: Build
FROM rust:1.75-slim as builder

WORKDIR /app

# Instalar dependencias de compilación
RUN apt-get update && apt-get install -y \
    pkg-config \
    libssl-dev \
    && rm -rf /var/lib/apt/lists/*

# Copiar manifiestos
COPY Cargo.toml Cargo.lock ./

# Copiar código fuente
COPY src ./src

# Compilar en modo release (optimizado)
RUN cargo build --release

# Stage 2: Runtime
FROM debian:bookworm-slim

WORKDIR /app

# Instalar solo dependencias de runtime
RUN apt-get update && apt-get install -y \
    ca-certificates \
    libssl3 \
    && rm -rf /var/lib/apt/lists/*

# Copiar binario compilado
COPY --from=builder /app/target/release/security-service /app/security-service

# Variables de entorno
ENV SECURITY_SERVICE_ENV=production
ENV SECURITY_SERVICE_LOG_LEVEL=info
ENV SECURITY_SERVICE_PORT=9000

# Healthcheck
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:9000/health || exit 1

# Ejecutar
EXPOSE 9000
CMD ["/app/security-service"]
