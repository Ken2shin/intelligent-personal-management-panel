@echo off
title Lanzador Maestro SaaS - Panel Inteligente
color 0B
cls

echo ======================================================
echo   INICIANDO ECOSISTEMA MULTI-TECNOLOGIA (V2.0)
echo ======================================================

:: 1. SEGURIDAD (RUST) - Puerto 9000
echo [1/4] Levantando Rust Security...
start "Rust-Security" cmd /k "cd services\rust && target\release\security-service.exe"

:: 2. NOTIFICACIONES (GO) - Puerto 8080
echo [2/4] Levantando Go Notifications...
:: Se ejecuta en Standalone si Redis no está
start "Go-Notifications" cmd /k "cd services\go && notification-service.exe"

:: 3. PUENTE DE DATOS (SWIFT) - Puerto 8090
echo [3/4] Levantando Swift Bridge...
if exist "services\swift\.build\release\swift.exe" (
    start "Swift-Service" cmd /k "cd services\swift && .build\release\swift.exe"
) else (
    start "Swift-Service" cmd /k "cd services\swift && swift run"
)

:: 4. BACKEND (LARAVEL) - Puerto 8000
echo [4/4] Levantando Laravel Core...
:: Recuerda: El .env debe tener los '#' en las lineas decorativas
start "Laravel-Core" cmd /k "php artisan serve"

echo.
echo Todo el ecosistema esta intentando sincronizarse...
pause