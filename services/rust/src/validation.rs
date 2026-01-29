use actix_web::{web, HttpResponse, error};
use serde::{Deserialize, Serialize};
use regex::Regex;
use lazy_static::lazy_static;
use tracing::{info, warn};

#[derive(Debug, Serialize, Deserialize)]
pub struct ValidateEmailRequest {
    email: String,
}

#[derive(Debug, Serialize, Deserialize)]
pub struct ValidatePasswordRequest {
    password: String,
}

#[derive(Debug, Serialize, Deserialize)]
pub struct ValidateInputRequest {
    input: String,
    #[serde(default)]
    input_type: Option<String>,
}

#[derive(Debug, Serialize, Deserialize)]
pub struct CheckSqlInjectionRequest {
    input: String,
}

#[derive(Debug, Serialize, Deserialize)]
pub struct ValidationResponse {
    valid: bool,
    #[serde(skip_serializing_if = "Option::is_none")]
    message: Option<String>,
    #[serde(skip_serializing_if = "Option::is_none")]
    score: Option<u32>,
}

lazy_static! {
    // Regex compilados una sola vez para mejor rendimiento
    static ref EMAIL_REGEX: Regex = Regex::new(
        r"^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$"
    ).unwrap();

    static ref SQL_INJECTION_PATTERNS: Vec<Regex> = vec![
        Regex::new(r"(?i)(union.*select|select.*from|insert.*into|delete.*from|drop.*table|update.*set)").unwrap(),
        Regex::new(r"(?i)(exec|execute|script|eval|alert|javascript)").unwrap(),
        Regex::new(r"('--|;|#|/\*|\*/|\*/)").unwrap(),
        Regex::new(r"(?i)(\bor\b.*=|\band\b.*=|xp_|sp_)").unwrap(),
    ];

    static ref XSS_PATTERNS: Vec<Regex> = vec![
        Regex::new(r"(?i)(<script|<iframe|<object|<embed|<img|on\w+\s*=)").unwrap(),
        Regex::new(r"(?i)(javascript:|vbscript:|data:text)").unwrap(),
        Regex::new(r#"(?i)(alert|confirm|prompt|eval|expression)\s*\("#).unwrap(),
    ];

    static ref COMMAND_INJECTION_PATTERNS: Vec<Regex> = vec![
        Regex::new(r"[;|&$`\\]").unwrap(),
        Regex::new(r"(?i)(bash|sh|cmd|powershell|bash|telnet)").unwrap(),
    ];
}

// ======================================================
// VALIDACIÓN DE EMAIL
// ======================================================

pub async fn validate_email(
    req: web::Json<ValidateEmailRequest>,
) -> Result<HttpResponse, error::Error> {
    info!("Validando email: {}", masked_email(&req.email));

    if req.email.is_empty() || req.email.len() > 254 {
        return Ok(HttpResponse::Ok().json(ValidationResponse {
            valid: false,
            message: Some("Invalid email length".to_string()),
            score: None,
        }));
    }

    let is_valid = EMAIL_REGEX.is_match(&req.email);

    Ok(HttpResponse::Ok().json(ValidationResponse {
        valid: is_valid,
        message: if is_valid {
            Some("Email format is valid".to_string())
        } else {
            Some("Invalid email format".to_string())
        },
        score: None,
    }))
}

// ======================================================
// VALIDACIÓN DE FORTALEZA DE CONTRASEÑA
// ======================================================

pub async fn validate_password_strength(
    req: web::Json<ValidatePasswordRequest>,
) -> Result<HttpResponse, error::Error> {
    info!("Evaluando fortaleza de contraseña");

    let password = &req.password;

    if password.is_empty() || password.len() < 8 {
        return Ok(HttpResponse::Ok().json(ValidationResponse {
            valid: false,
            message: Some("Password must be at least 8 characters".to_string()),
            score: Some(0),
        }));
    }

    let mut score = 0;
    let mut feedback = Vec::new();

    // Longitud
    if password.len() >= 8 {
        score += 20;
    }
    if password.len() >= 12 {
        score += 10;
    }
    if password.len() >= 16 {
        score += 10;
    }

    // Mayúsculas
    if password.chars().any(|c| c.is_uppercase()) {
        score += 15;
        feedback.push("Contains uppercase letters");
    }

    // Minúsculas
    if password.chars().any(|c| c.is_lowercase()) {
        score += 15;
        feedback.push("Contains lowercase letters");
    }

    // Números
    if password.chars().any(|c| c.is_numeric()) {
        score += 15;
        feedback.push("Contains numbers");
    }

    // Caracteres especiales
    if password.chars().any(|c| "!@#$%^&*()_+-=[]{}|;:,.<>?".contains(c)) {
        score += 20;
        feedback.push("Contains special characters");
    }

    // Evitar patrones comunes
    if is_common_password(password) {
        score = (score / 2).max(10);
        feedback.push("Pattern appears to be common");
    }

    score = score.min(100);

    Ok(HttpResponse::Ok().json(ValidationResponse {
        valid: score >= 60,
        message: Some(feedback.join(", ")),
        score: Some(score),
    }))
}

fn is_common_password(password: &str) -> bool {
    let password_lower = password.to_lowercase();
    let common = [
        "password", "123456", "qwerty", "admin", "letmein", 
        "welcome", "monkey", "shadow", "sunshine", "princess"
    ];
    
    common.iter().any(|&p| password_lower.contains(p))
}

// ======================================================
// VALIDACIÓN DE ENTRADA (INPUT SANITIZATION)
// ======================================================

pub async fn validate_input(
    req: web::Json<ValidateInputRequest>,
) -> Result<HttpResponse, error::Error> {
    info!("Validando entrada de usuario");

    let input_type = req.input_type.as_deref().unwrap_or("text");

    // Validar longitud
    if req.input.is_empty() || req.input.len() > 10_000 {
        return Ok(HttpResponse::Ok().json(ValidationResponse {
            valid: false,
            message: Some("Invalid input length".to_string()),
            score: None,
        }));
    }

    // Verificar XSS
    if has_xss_payload(&req.input) {
        warn!("Intento de XSS detectado");
        return Ok(HttpResponse::Ok().json(ValidationResponse {
            valid: false,
            message: Some("XSS payload detected".to_string()),
            score: None,
        }));
    }

    // Verificar según tipo
    let is_valid = match input_type {
        "email" => EMAIL_REGEX.is_match(&req.input),
        "url" => req.input.starts_with("http://") || req.input.starts_with("https://"),
        "number" => req.input.parse::<f64>().is_ok(),
        "alphanumeric" => req.input.chars().all(|c| c.is_alphanumeric() || c.is_whitespace()),
        _ => !has_sql_injection(&req.input),
    };

    Ok(HttpResponse::Ok().json(ValidationResponse {
        valid: is_valid,
        message: if is_valid {
            Some("Input is valid".to_string())
        } else {
            Some(format!("Invalid input for type: {}", input_type))
        },
        score: None,
    }))
}

// ======================================================
// DETECCIÓN DE SQL INJECTION
// ======================================================

pub async fn check_sql_injection(
    req: web::Json<CheckSqlInjectionRequest>,
) -> Result<HttpResponse, error::Error> {
    info!("Verificando SQL injection");

    if has_sql_injection(&req.input) {
        warn!("Intento de SQL injection detectado");
        return Ok(HttpResponse::Ok().json(ValidationResponse {
            valid: false,
            message: Some("SQL injection pattern detected".to_string()),
            score: None,
        }));
    }

    Ok(HttpResponse::Ok().json(ValidationResponse {
        valid: true,
        message: Some("No SQL injection detected".to_string()),
        score: None,
    }))
}

fn has_sql_injection(input: &str) -> bool {
    SQL_INJECTION_PATTERNS.iter().any(|pattern| pattern.is_match(input))
}

fn has_xss_payload(input: &str) -> bool {
    XSS_PATTERNS.iter().any(|pattern| pattern.is_match(input))
}

// ======================================================
// UTILIDADES
// ======================================================

fn masked_email(email: &str) -> String {
    if let Some(at_index) = email.find('@') {
        if at_index > 2 {
            format!("{}...{}", &email[0..2], &email[at_index..])
        } else {
            email.to_string()
        }
    } else {
        "***".to_string()
    }
}
