mod crypto;
mod validation;
mod rate_limiter;
mod audit;
mod config;

use actix_web::{web, App, HttpServer, HttpResponse, middleware};
use std::sync::Arc;
use tracing::info; // CORRECCIÓN: eliminado 'error' que no se usaba
use crate::config::SecurityConfig;
use crate::rate_limiter::RateLimiter;

#[actix_web::main]
async fn main() -> std::io::Result<()> {
    // Inicializar tracing para auditoría
    tracing_subscriber::fmt()
        .with_env_filter(
            tracing_subscriber::EnvFilter::from_default_env()
                .add_directive("security_service=debug".parse().unwrap()),
        )
        .init();

    info!("Iniciando Security Service en modo producción");

    let config = SecurityConfig::from_env();
    let rate_limiter = Arc::new(RateLimiter::new(
        config.rate_limit_requests,
        config.rate_limit_window_secs,
    ));

    let config_data = web::Data::new(config);
    let rate_limiter_data = web::Data::new(rate_limiter);

    HttpServer::new(move || {
        App::new()
            .app_data(config_data.clone())
            .app_data(rate_limiter_data.clone())
            .wrap(middleware::Logger::default())
            .wrap(middleware::NormalizePath::trim())
            // ==========================================
            // ENDPOINTS DE ENCRIPTACIÓN
            // ==========================================
            .route("/api/v1/crypto/encrypt", web::post().to(crypto::encrypt_data))
            .route("/api/v1/crypto/decrypt", web::post().to(crypto::decrypt_data))
            .route("/api/v1/crypto/hash", web::post().to(crypto::hash_password))
            .route("/api/v1/crypto/verify-hash", web::post().to(crypto::verify_password_hash))
            // ==========================================
            // ENDPOINTS DE VALIDACIÓN
            // ==========================================
            .route("/api/v1/validate/email", web::post().to(validation::validate_email))
            .route("/api/v1/validate/password-strength", web::post().to(validation::validate_password_strength))
            .route("/api/v1/validate/input", web::post().to(validation::validate_input))
            .route("/api/v1/validate/sql-injection", web::post().to(validation::check_sql_injection))
            // ==========================================
            // ENDPOINTS DE SEGURIDAD
            // ==========================================
            .route("/api/v1/security/generate-token", web::post().to(crypto::generate_secure_token))
            .route("/api/v1/security/verify-token", web::post().to(crypto::verify_secure_token))
            .route("/api/v1/security/rate-limit-check", web::post().to(rate_limiter::check_rate_limit))
            // ==========================================
            // ENDPOINT DE SALUD
            // ==========================================
            .route("/health", web::get().to(health_check))
    })
    .bind("0.0.0.0:9000")?
    .run()
    .await
}

async fn health_check() -> HttpResponse {
    HttpResponse::Ok().json(serde_json::json!({
        "status": "healthy",
        "service": "security-service",
        "version": "1.0.0"
    }))
}