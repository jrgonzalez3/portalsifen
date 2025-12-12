@echo off
echo ========================================
echo ARREGLANDO SIFEN PANEL
echo ========================================
echo.

REM Crear .env si no existe
if not exist ".env" (
    echo Creando .env desde .env.example...
    copy .env.example .env
)

echo Configurando variables de entorno...
echo.

REM Agregar ASSET_URL si no existe
findstr /C:"ASSET_URL" .env >nul
if errorlevel 1 (
    echo ASSET_URL=/portalsifen >> .env
    echo [OK] ASSET_URL agregado
) else (
    echo [OK] ASSET_URL ya existe
)

REM Agregar AUTO_LOGIN_ENABLED si no existe
findstr /C:"AUTO_LOGIN_ENABLED" .env >nul
if errorlevel 1 (
    echo AUTO_LOGIN_ENABLED=true >> .env
    echo [OK] AUTO_LOGIN_ENABLED agregado
) else (
    echo [OK] AUTO_LOGIN_ENABLED ya existe
)

REM Agregar AUTO_LOGIN_EMAIL si no existe
findstr /C:"AUTO_LOGIN_EMAIL" .env >nul
if errorlevel 1 (
    echo AUTO_LOGIN_EMAIL=admin@sifen.local >> .env
    echo [OK] AUTO_LOGIN_EMAIL agregado
) else (
    echo [OK] AUTO_LOGIN_EMAIL ya existe
)

REM Agregar AUTO_LOGIN_PASSWORD si no existe
findstr /C:"AUTO_LOGIN_PASSWORD" .env >nul
if errorlevel 1 (
    echo AUTO_LOGIN_PASSWORD=admin123 >> .env
    echo [OK] AUTO_LOGIN_PASSWORD agregado
) else (
    echo [OK] AUTO_LOGIN_PASSWORD ya existe
)

echo.
echo Publicando assets de Livewire...
php artisan livewire:publish

echo.
echo Limpiando caches...
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan cache:clear

echo.
echo ========================================
echo TODO LISTO!
echo ========================================
echo.
echo Ahora accede a: http://10.99.99.56:8080/portalsifen/admin
echo.
echo El sistema deberia hacer auto-login automaticamente
echo Si no funciona, usa:
echo   Email: admin@sifen.local
echo   Password: admin123
echo.
pause
