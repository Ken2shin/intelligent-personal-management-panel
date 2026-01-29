use actix_web::{web, HttpResponse, error};
use serde::{Deserialize, Serialize};
use std::collections::HashMap;
use std::sync::Arc;
use parking_lot::Mutex;
use chrono::{Utc, Duration};
use governor::{Quota, RateLimiter as GovernorRateLimiter};
// CORRECCIÓN: Importar tipos necesarios para definir los Genéricos
use governor::state::{InMemoryState, NotKeyed};
use governor::clock::DefaultClock;
use std::num::NonZeroU32;
use tracing::{warn, info};

#[derive(Debug, Serialize, Deserialize)]
pub struct RateLimitCheckRequest {
    client_id: String,
}

#[derive(Debug, Serialize, Deserialize)]
pub struct RateLimitResponse {
    allowed: bool,
    remaining: u32,
    reset_at: String,
    #[serde(skip_serializing_if = "Option::is_none")]
    message: Option<String>,
}

pub struct RateLimiter {
    limiters: Mutex<HashMap<String, RateLimitData>>,
    requests_per_window: u32,
    window_seconds: u64,
}

struct RateLimitData {
    // CORRECCIÓN: Definición explícita de tipos genéricos para evitar error "missing generics"
    governor: GovernorRateLimiter<NotKeyed, InMemoryState, DefaultClock>,
    last_reset: chrono::DateTime<Utc>,
}

impl RateLimiter {
    pub fn new(requests: u32, window_secs: u64) -> Self {
        RateLimiter {
            limiters: Mutex::new(HashMap::new()),
            requests_per_window: requests,
            window_seconds: window_secs,
        }
    }

    pub fn check(&self, client_id: &str) -> (bool, u32, chrono::DateTime<Utc>) {
        let mut limiters = self.limiters.lock();
        let now = Utc::now();

        let data = limiters
            .entry(client_id.to_string())
            .or_insert_with(|| {
                RateLimitData {
                    governor: GovernorRateLimiter::direct(
                        Quota::per_second(NonZeroU32::new(self.requests_per_window).unwrap())
                    ),
                    last_reset: now,
                }
            });

        // Resetear si la ventana pasó
        if now.signed_duration_since(data.last_reset).num_seconds() as u64 > self.window_seconds {
            data.last_reset = now;
            data.governor = GovernorRateLimiter::direct(
                Quota::per_second(NonZeroU32::new(self.requests_per_window).unwrap())
            );
        }

        let allowed = data.governor.check().is_ok();
        let reset_at = data.last_reset + Duration::seconds(self.window_seconds as i64);
        let remaining = self.requests_per_window; // Simplificado

        if !allowed {
            warn!("Rate limit exceeded for client: {}", client_id);
        } else {
            info!("Request allowed for client: {}", client_id);
        }

        (allowed, remaining, reset_at)
    }
}

pub async fn check_rate_limit(
    req: web::Json<RateLimitCheckRequest>,
    limiter: web::Data<Arc<RateLimiter>>,
) -> Result<HttpResponse, error::Error> {
    let (allowed, remaining, reset_at) = limiter.check(&req.client_id);

    if allowed {
        Ok(HttpResponse::Ok().json(RateLimitResponse {
            allowed: true,
            remaining,
            reset_at: reset_at.to_rfc3339(),
            message: None,
        }))
    } else {
        Ok(HttpResponse::TooManyRequests().json(RateLimitResponse {
            allowed: false,
            remaining: 0,
            reset_at: reset_at.to_rfc3339(),
            message: Some("Rate limit exceeded".to_string()),
        }))
    }
}