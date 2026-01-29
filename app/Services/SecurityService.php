<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class SecurityService
{
    private string $baseUrl;
    private string $apiKey;
    private string $jwtSecret;

    public function __construct()
    {
        $this->baseUrl = config('services.security.url', 'http://security-service:9000');
        $this->apiKey = config('services.security.key', '');
        $this->jwtSecret = config('services.security.jwt_secret', '');
    }

    /**
     * Encriptar datos sensibles (AES-256-GCM)
     */
    public function encryptData(array $data, ?string $userId = null): array
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders(['Authorization' => "Bearer {$this->apiKey}"])
                ->post("{$this->baseUrl}/api/v1/encrypt", [
                    'data' => $data,
                    'user_id' => $userId,
                    'timestamp' => now()->timestamp,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Encryption failed', ['status' => $response->status()]);
            throw new Exception('Encryption service failed');
        } catch (Exception $e) {
            Log::error('Security Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Desencriptar datos
     */
    public function decryptData(string $encrypted, ?string $userId = null): array
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders(['Authorization' => "Bearer {$this->apiKey}"])
                ->post("{$this->baseUrl}/api/v1/decrypt", [
                    'encrypted' => $encrypted,
                    'user_id' => $userId,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Decryption service failed');
        } catch (Exception $e) {
            Log::error('Security Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Validar entrada contra XSS, SQL Injection, etc
     */
    public function validateInput(string $input, string $type = 'text'): array
    {
        try {
            $response = Http::timeout(5)
                ->withHeaders(['Authorization' => "Bearer {$this->apiKey}"])
                ->post("{$this->baseUrl}/api/v1/validate", [
                    'input' => $input,
                    'type' => $type, // 'text', 'email', 'url', 'sql', etc
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Validation service failed');
        } catch (Exception $e) {
            Log::error('Security Validation Error: ' . $e->getMessage());
            return ['is_safe' => false, 'message' => 'Validation error'];
        }
    }

    /**
     * Hash seguro de contraseña (Argon2id)
     */
    public function hashPassword(string $password): string
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders(['Authorization' => "Bearer {$this->apiKey}"])
                ->post("{$this->baseUrl}/api/v1/hash-password", [
                    'password' => $password,
                ]);

            if ($response->successful()) {
                return $response->json()['hash'];
            }

            throw new Exception('Password hashing failed');
        } catch (Exception $e) {
            Log::error('Password Hash Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verificar contraseña contra hash
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders(['Authorization' => "Bearer {$this->apiKey}"])
                ->post("{$this->baseUrl}/api/v1/verify-password", [
                    'password' => $password,
                    'hash' => $hash,
                ]);

            if ($response->successful()) {
                return $response->json()['valid'] ?? false;
            }

            return false;
        } catch (Exception $e) {
            Log::error('Password Verify Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generar token JWT seguro
     */
    public function generateJWT(array $claims): string
    {
        try {
            $response = Http::timeout(5)
                ->withHeaders(['Authorization' => "Bearer {$this->apiKey}"])
                ->post("{$this->baseUrl}/api/v1/generate-jwt", [
                    'claims' => $claims,
                    'secret' => $this->jwtSecret,
                ]);

            if ($response->successful()) {
                return $response->json()['token'];
            }

            throw new Exception('JWT generation failed');
        } catch (Exception $e) {
            Log::error('JWT Generation Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verificar JWT
     */
    public function verifyJWT(string $token): array
    {
        try {
            $response = Http::timeout(5)
                ->withHeaders(['Authorization' => "Bearer {$this->apiKey}"])
                ->post("{$this->baseUrl}/api/v1/verify-jwt", [
                    'token' => $token,
                    'secret' => $this->jwtSecret,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return ['valid' => false];
        } catch (Exception $e) {
            Log::error('JWT Verification Error: ' . $e->getMessage());
            return ['valid' => false];
        }
    }

    /**
     * Registrar evento de auditoría
     */
    public function auditLog(string $action, string $entity, string $entityId, array $details = [], ?string $userId = null): void
    {
        try {
            Http::timeout(5)
                ->withHeaders(['Authorization' => "Bearer {$this->apiKey}"])
                ->post("{$this->baseUrl}/api/v1/audit-log", [
                    'action' => $action,
                    'entity' => $entity,
                    'entity_id' => $entityId,
                    'user_id' => $userId,
                    'details' => $details,
                    'timestamp' => now()->timestamp,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
        } catch (Exception $e) {
            Log::warning('Audit log failed: ' . $e->getMessage());
        }
    }

    /**
     * Verificar rate limit
     */
    public function checkRateLimit(string $identifier, int $maxRequests = 100, int $windowSeconds = 60): bool
    {
        try {
            $response = Http::timeout(3)
                ->withHeaders(['Authorization' => "Bearer {$this->apiKey}"])
                ->post("{$this->baseUrl}/api/v1/rate-limit", [
                    'identifier' => $identifier,
                    'max_requests' => $maxRequests,
                    'window_seconds' => $windowSeconds,
                ]);

            if ($response->successful()) {
                return $response->json()['allowed'] ?? false;
            }

            return false;
        } catch (Exception $e) {
            Log::warning('Rate limit check failed: ' . $e->getMessage());
            return true; // Allow if service fails
        }
    }
}
