use chrono::Utc;
use serde::{Deserialize, Serialize};
use tracing::info;

#[derive(Debug, Serialize, Deserialize)]
pub struct AuditLog {
    pub timestamp: String,
    pub event: String,
    pub client_id: String,
    pub ip_address: String,
    pub action: String,
    pub status: String,
    pub details: Option<String>,
}

impl AuditLog {
    pub fn new(
        event: &str,
        client_id: &str,
        ip_address: &str,
        action: &str,
        status: &str,
    ) -> Self {
        let log = AuditLog {
            timestamp: Utc::now().to_rfc3339(),
            event: event.to_string(),
            client_id: client_id.to_string(),
            ip_address: ip_address.to_string(),
            action: action.to_string(),
            status: status.to_string(),
            details: None,
        };

        // Registrar en logs
        info!(
            "AUDIT: {} - {} - {} - {}",
            event, client_id, action, status
        );

        log
    }

    pub fn with_details(mut self, details: &str) -> Self {
        self.details = Some(details.to_string());
        self
    }
}

// En producción, estos logs se enviarían a:
// - Elasticsearch para análisis
// - Splunk para monitoreo
// - Base de datos para auditoría de cumplimiento
// - Sistema de alertas para eventos sospechosos
