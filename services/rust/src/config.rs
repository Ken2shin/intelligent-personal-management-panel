use serde::{Deserialize, Serialize};
use std::env;

#[derive(Debug, Clone, Serialize, Deserialize)]
pub struct SecurityConfig {
    pub service_name: String,
    pub port: u16,
    pub host: String,
    pub environment: String,
    pub log_level: String,
    pub rate_limit_requests: u32,
    pub rate_limit_window_secs: u64,
    pub jwt_secret: String,
    pub jwt_expiration_hours: i64,
    pub cors_allowed_origins: Vec<String>,
    pub enable_audit_logging: bool,
    pub max_request_size: usize,
}

impl SecurityConfig {
    pub fn from_env() -> Self {
        let env_prefix = "SECURITY_SERVICE_";

        SecurityConfig {
            service_name: env::var(&format!("{}NAME", env_prefix))
                .unwrap_or_else(|_| "security-service".to_string()),
            
            port: env::var(&format!("{}PORT", env_prefix))
                .unwrap_or_else(|_| "9000".to_string())
                .parse()
                .unwrap_or(9000),
            
            host: env::var(&format!("{}HOST", env_prefix))
                .unwrap_or_else(|_| "0.0.0.0".to_string()),
            
            environment: env::var(&format!("{}ENV", env_prefix))
                .unwrap_or_else(|_| "production".to_string()),
            
            log_level: env::var(&format!("{}LOG_LEVEL", env_prefix))
                .unwrap_or_else(|_| "info".to_string()),
            
            rate_limit_requests: env::var(&format!("{}RATE_LIMIT_REQ", env_prefix))
                .unwrap_or_else(|_| "100".to_string())
                .parse()
                .unwrap_or(100),
            
            rate_limit_window_secs: env::var(&format!("{}RATE_LIMIT_WINDOW", env_prefix))
                .unwrap_or_else(|_| "60".to_string())
                .parse()
                .unwrap_or(60),
            
            jwt_secret: env::var(&format!("{}JWT_SECRET", env_prefix))
                .unwrap_or_else(|_| "change-me-in-production".to_string()),
            
            jwt_expiration_hours: env::var(&format!("{}JWT_EXP", env_prefix))
                .unwrap_or_else(|_| "24".to_string())
                .parse()
                .unwrap_or(24),
            
            cors_allowed_origins: env::var(&format!("{}CORS_ORIGINS", env_prefix))
                .unwrap_or_else(|_| "http://localhost:3000,http://localhost:8000".to_string())
                .split(',')
                .map(|s| s.trim().to_string())
                .collect(),
            
            enable_audit_logging: env::var(&format!("{}AUDIT_LOG", env_prefix))
                .unwrap_or_else(|_| "true".to_string())
                .to_lowercase() == "true",
            
            max_request_size: env::var(&format!("{}MAX_REQ_SIZE", env_prefix))
                .unwrap_or_else(|_| "1048576".to_string()) // 1MB por defecto
                .parse()
                .unwrap_or(1048576),
        }
    }
}

impl Default for SecurityConfig {
    fn default() -> Self {
        Self::from_env()
    }
}
