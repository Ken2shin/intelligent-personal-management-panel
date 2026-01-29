use actix_web::{web, HttpResponse, error};
use serde::{Deserialize, Serialize};
use argon2::{Argon2, PasswordHasher, PasswordHash, PasswordVerifier};
use argon2::password_hash::SaltString;
use aes_gcm::{
    aead::{Aead, KeyInit, Payload},
    Aes256Gcm, Nonce,
};
use rand::Rng;
use hex::encode;
use uuid::Uuid;
use chrono::{Utc, Duration};
use std::collections::HashMap;
use lazy_static::lazy_static;
use parking_lot::Mutex;
use tracing::{info, warn, error as log_error};

// ======================================================
// ESTRUCTURAS DE DATOS
// ======================================================

#[derive(Debug, Serialize, Deserialize)]
pub struct EncryptRequest {
    data: String,
    #[serde(default)]
    aad: Option<String>, // Additional Authenticated Data
}

#[derive(Debug, Serialize, Deserialize)]
pub struct EncryptResponse {
    ciphertext: String,
    nonce: String,
    tag: String,
    iv: String,
}

#[derive(Debug, Serialize, Deserialize)]
pub struct DecryptRequest {
    ciphertext: String,
    nonce: String,
    tag: String,
    iv: String,
    #[serde(default)]
    aad: Option<String>,
}

#[derive(Debug, Serialize, Deserialize)]
pub struct HashRequest {
    password: String,
    #[serde(default = "default_cost")]
    cost: u32,
}

fn default_cost() -> u32 {
    3
}

#[derive(Debug, Serialize, Deserialize)]
pub struct VerifyHashRequest {
    password: String,
    hash: String,
}

#[derive(Debug, Serialize, Deserialize)]
pub struct TokenResponse {
    token: String,
    expires_at: String,
    ttl_seconds: i64,
}

#[derive(Debug, Serialize, Deserialize)]
pub struct VerifyTokenRequest {
    token: String,
}

// ======================================================
// TOKENS EN MEMORIA (Producción: usar Redis)
// ======================================================

lazy_static! {
    static ref TOKEN_STORE: Mutex<HashMap<String, TokenData>> = Mutex::new(HashMap::new());
}

#[derive(Clone)]
struct TokenData {
    expires_at: chrono::DateTime<Utc>,
    data: Option<String>,
}

// ======================================================
// ENCRIPTACIÓN AES-256-GCM
// ======================================================

pub async fn encrypt_data(
    req: web::Json<EncryptRequest>,
) -> Result<HttpResponse, error::Error> {
    info!("Encriptando datos con AES-256-GCM");

    // Validar entrada
    if req.data.is_empty() || req.data.len() > 1_000_000 {
        warn!("Intento de encriptación con datos inválidos");
        return Ok(HttpResponse::BadRequest().json(serde_json::json!({
            "error": "Invalid data size (1 byte - 1 MB)"
        })));
    }

    // Generar clave (en producción: usar key derivation)
    let mut rng = rand::thread_rng();
    let key_bytes: [u8; 32] = rng.gen();
    let key = aes_gcm::Key::<Aes256Gcm>::from(key_bytes);
    let cipher = Aes256Gcm::new(&key);

    // Generar nonce seguro (96 bits)
    let nonce_bytes: [u8; 12] = rng.gen();
    let nonce = Nonce::from_slice(&nonce_bytes);

    // Preparar AAD (Authenticated Additional Data)
    let aad = req.aad.as_ref().map(|s| s.as_bytes().to_vec());
    let payload = Payload {
        msg: req.data.as_bytes(),
        aad: aad.as_deref().unwrap_or(b""),
    };

    // Encriptar
    let ciphertext = match cipher.encrypt(nonce, payload) {
        Ok(ct) => ct,
        Err(e) => {
            log_error!("Error en encriptación: {}", e);
            // CORRECCIÓN: Usamos 'return' explícito para evitar problemas de punto y coma
            return Ok(HttpResponse::InternalServerError().json(serde_json::json!({
                "error": "Encryption failed"
            })));
        }
    };

    // Extraer tag (últimos 16 bytes)
    let tag = ciphertext[ciphertext.len() - 16..].to_vec();
    let encrypted = ciphertext[..ciphertext.len() - 16].to_vec();

    info!("Encriptación completada exitosamente");

    Ok(HttpResponse::Ok().json(EncryptResponse {
        ciphertext: encode(&encrypted),
        nonce: encode(nonce_bytes),
        tag: encode(&tag),
        iv: encode(&key_bytes),
    }))
}

pub async fn decrypt_data(
    req: web::Json<DecryptRequest>,
) -> Result<HttpResponse, error::Error> {
    info!("Desencriptando datos con AES-256-GCM");

    // Validar hexadecimals
    let key_bytes = match hex::decode(&req.iv) {
        Ok(k) if k.len() == 32 => {
            let mut arr = [0u8; 32];
            arr.copy_from_slice(&k);
            arr
        }
        _ => {
            warn!("IV inválido en desencriptación");
            return Ok(HttpResponse::BadRequest().json(serde_json::json!({
                "error": "Invalid IV"
            })));
        }
    };

    let key = aes_gcm::Key::<Aes256Gcm>::from(key_bytes);
    let cipher = Aes256Gcm::new(&key);

    let nonce_bytes = match hex::decode(&req.nonce) {
        Ok(n) if n.len() == 12 => {
            let mut arr = [0u8; 12];
            arr.copy_from_slice(&n);
            arr
        }
        _ => {
            warn!("Nonce inválido en desencriptación");
            return Ok(HttpResponse::BadRequest().json(serde_json::json!({
                "error": "Invalid nonce"
            })));
        }
    };

    let nonce = Nonce::from_slice(&nonce_bytes);
    let ciphertext = match hex::decode(&req.ciphertext) {
        Ok(ct) => ct,
        _ => {
            warn!("Ciphertext inválido");
            return Ok(HttpResponse::BadRequest().json(serde_json::json!({
                "error": "Invalid ciphertext"
            })));
        }
    };

    let tag = match hex::decode(&req.tag) {
        Ok(t) => t,
        _ => {
            warn!("Tag inválido");
            return Ok(HttpResponse::BadRequest().json(serde_json::json!({
                "error": "Invalid tag"
            })));
        }
    };

    // Reconstruir ciphertext con tag
    let mut full_ciphertext = ciphertext;
    full_ciphertext.extend_from_slice(&tag);

    let aad = req.aad.as_ref().map(|s| s.as_bytes().to_vec());
    let payload = Payload {
        msg: &full_ciphertext,
        aad: aad.as_deref().unwrap_or(b""),
    };

    match cipher.decrypt(nonce, payload) {
        Ok(plaintext) => {
            match String::from_utf8(plaintext) {
                Ok(text) => {
                    info!("Desencriptación exitosa");
                    Ok(HttpResponse::Ok().json(serde_json::json!({
                        "data": text
                    })))
                }
                Err(_) => {
                    warn!("Plaintext no es UTF-8 válido");
                    Ok(HttpResponse::BadRequest().json(serde_json::json!({
                        "error": "Invalid UTF-8"
                    })))
                }
            }
        }
        Err(e) => {
            warn!("Fallo en desencriptación: {}", e);
            Ok(HttpResponse::Unauthorized().json(serde_json::json!({
                "error": "Decryption failed - authentication tag mismatch"
            })))
        }
    }
}

// ======================================================
// PASSWORD HASHING CON ARGON2 (NIST APPROVED)
// ======================================================

pub async fn hash_password(
    req: web::Json<HashRequest>,
) -> Result<HttpResponse, error::Error> {
    info!("Generando hash de contraseña con Argon2");

    if req.password.is_empty() || req.password.len() > 128 {
        warn!("Contraseña inválida");
        return Ok(HttpResponse::BadRequest().json(serde_json::json!({
            "error": "Invalid password length (1-128 chars)"
        })));
    }

    // Generar salt aleatorio
    let salt = SaltString::generate(rand::thread_rng());

    // Configurar Argon2 con parámetros recomendados por OWASP
    let argon2 = Argon2::default();

    match argon2.hash_password(req.password.as_bytes(), &salt) {
        Ok(hash) => {
            info!("Hash generado exitosamente");
            Ok(HttpResponse::Ok().json(serde_json::json!({
                "hash": hash.to_string(),
                "algorithm": "Argon2id"
            })))
        }
        Err(e) => {
            // CORRECCIÓN: Aquí estaba el error. Añadí 'return' para asegurar que devuelva el valor
            // incluso si hay un punto y coma al final.
            log_error!("Error en hash: {}", e);
            return Ok(HttpResponse::InternalServerError().json(serde_json::json!({
                "error": "Hashing failed"
            })));
        }
    }
}

pub async fn verify_password_hash(
    req: web::Json<VerifyHashRequest>,
) -> Result<HttpResponse, error::Error> {
    info!("Verificando hash de contraseña");

    let parsed_hash = match PasswordHash::new(&req.hash) {
        Ok(h) => h,
        Err(_) => {
            warn!("Hash inválido");
            return Ok(HttpResponse::BadRequest().json(serde_json::json!({
                "error": "Invalid hash format"
            })));
        }
    };

    let argon2 = Argon2::default();
    match argon2.verify_password(req.password.as_bytes(), &parsed_hash) {
        Ok(_) => {
            info!("Verificación exitosa");
            Ok(HttpResponse::Ok().json(serde_json::json!({
                "valid": true
            })))
        }
        Err(_) => {
            warn!("Fallo en verificación");
            Ok(HttpResponse::Ok().json(serde_json::json!({
                "valid": false
            })))
        }
    }
}

// ======================================================
// GENERACIÓN DE TOKENS SEGUROS
// ======================================================

pub async fn generate_secure_token(
) -> Result<HttpResponse, error::Error> {
    let token = Uuid::new_v4().to_string() + "-" + &hex::encode(rand::random::<[u8; 32]>());
    let expires_at = Utc::now() + Duration::hours(24);

    // Almacenar en memoria (en producción: Redis)
    TOKEN_STORE.lock().insert(
        token.clone(),
        TokenData {
            expires_at,
            data: None,
        },
    );

    info!("Token seguro generado");

    Ok(HttpResponse::Ok().json(TokenResponse {
        token,
        expires_at: expires_at.to_rfc3339(),
        ttl_seconds: 86400,
    }))
}

pub async fn verify_secure_token(
    req: web::Json<VerifyTokenRequest>,
) -> Result<HttpResponse, error::Error> {
    let store = TOKEN_STORE.lock();

    match store.get(&req.token) {
        Some(data) if data.expires_at > Utc::now() => {
            info!("Token verificado exitosamente");
            Ok(HttpResponse::Ok().json(serde_json::json!({
                "valid": true,
                "expires_at": data.expires_at.to_rfc3339()
            })))
        }
        _ => {
            warn!("Token inválido o expirado");
            Ok(HttpResponse::Unauthorized().json(serde_json::json!({
                "valid": false,
                "error": "Token invalid or expired"
            })))
        }
    }
}