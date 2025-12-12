@echo off
echo ========================================
echo Actualizando configuracion SIFEN Panel
echo ========================================
echo.

REM Verificar si existe .env
if not exist ".env" (
    echo Creando .env desde .env.example...
    copy .env.example .env
    echo.
)

echo Agregando ASSET_URL al .env...
echo.

REM Buscar si ya existe ASSET_URL
findstr /C:"ASSET_URL" .env >nul
if errorlevel 1 (
    echo ASSET_URL=/portalsifen >> .env
    echo ASSET_URL agregado
) else (
    echo ASSET_URL ya existe en .env
)

echo.
echo Limpiando caches...
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan cache:clear

echo.
echo ========================================
echo Configuracion actualizada!
echo ========================================
echo.
echo Ahora accede a: http://10.99.99.56:8080/portalsifen/admin
echo.
echo El sistema deberia hacer auto-login automaticamente
echo.
pause
