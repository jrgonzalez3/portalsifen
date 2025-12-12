@echo off
echo ========================================
echo ARREGLANDO LIVEWIRE - SIFEN PANEL
echo ========================================
echo.

echo Verificando archivo .env...
if not exist ".env" (
    echo ERROR: .env no existe
    echo Creando desde .env.example...
    copy .env.example .env
)

echo.
echo Agregando ASSET_URL=/portalsifen al .env...
echo.

REM Buscar si ya existe ASSET_URL
findstr /C:"ASSET_URL" .env >nul
if errorlevel 1 (
    echo ASSET_URL=/portalsifen >> .env
    echo [OK] ASSET_URL agregado
) else (
    echo [INFO] ASSET_URL ya existe, verificando valor...
    findstr /C:"ASSET_URL=/portalsifen" .env >nul
    if errorlevel 1 (
        echo [WARN] ASSET_URL tiene valor incorrecto
        echo [INFO] Por favor edita manualmente .env y cambia ASSET_URL a:
        echo ASSET_URL=/portalsifen
    ) else (
        echo [OK] ASSET_URL ya tiene el valor correcto
    )
)

echo.
echo Limpiando caches...
php artisan config:clear
php artisan view:clear
php artisan route:clear

echo.
echo ========================================
echo LISTO!
echo ========================================
echo.
echo Ahora recarga la pagina (Ctrl+F5):
echo http://10.99.99.56:8080/portalsifen/admin/login
echo.
echo Livewire deberia cargar desde:
echo http://10.99.99.56:8080/portalsifen/livewire/livewire.js
echo.
echo Credenciales:
echo Email: admin@sifen.local
echo Password: admin123
echo.
pause
