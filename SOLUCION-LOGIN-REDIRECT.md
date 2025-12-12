# Guía Rápida - Solución Login Redirect

## 🐛 Problema Identificado

**Síntoma**: Login acepta credenciales pero no redirige al dashboard

**Causa**: Nginx con subdirectorio `/portalsifen/` causa que Filament redirija a `/admin` en lugar de `/portalsifen/admin`

## ✅ Solución

### 1. Verificar `.env`

Asegúrate que tu archivo `.env` tenga:

```bash
APP_URL=http://10.99.99.56:8080/portalsifen
ASSET_URL=/portalsifen
```

### 2. Limpiar Cachés

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### 3. Probar Login

1. Acceder a: `http://10.99.99.56:8080/portalsifen/admin/login`
2. Usar credenciales:
   - Email: `admin@sifen.local`
   - Password: `admin123`
3. Debería redirigir a: `http://10.99.99.56:8080/portalsifen/admin`

## 🔍 Si No Funciona

Revisar en el navegador (F12 → Network):
- ¿A qué URL redirige después del POST a `/admin/login`?
- Si redirige a `/admin` (sin `/portalsifen/`), el problema es la configuración de APP_URL

## 📝 Credenciales Verificadas

- Email: `admin@sifen.local`
- Password: `admin123`
- Estado: ✅ Verificadas con script de diagnóstico
