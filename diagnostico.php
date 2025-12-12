<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "DIAGNOSTICO SIFEN PANEL\n";
echo "========================================\n\n";

// 1. Verificar configuración
echo "1. CONFIGURACION:\n";
echo "   APP_URL: " . config('app.url') . "\n";
echo "   ASSET_URL: " . config('app.asset_url') . "\n";
echo "   Environment: " . app()->environment() . "\n";
echo "   Debug: " . (config('app.debug') ? 'ENABLED' : 'DISABLED') . "\n\n";

// 2. Verificar usuario admin
echo "2. USUARIO ADMIN:\n";
$user = \App\Models\User::where('email', 'admin@sifen.local')->first();
if ($user) {
    echo "   ✓ Usuario encontrado: {$user->email}\n";
    echo "   ID: {$user->id}\n";
    echo "   Nombre: {$user->name}\n";
    
    // Verificar password
    if (\Hash::check('admin123', $user->password)) {
        echo "   ✓ Password 'admin123' es CORRECTO\n";
    } else {
        echo "   ✗ Password 'admin123' NO coincide\n";
        echo "   Actualizando password...\n";
        $user->password = bcrypt('admin123');
        $user->save();
        echo "   ✓ Password actualizado\n";
    }
} else {
    echo "   ✗ Usuario NO encontrado\n";
    echo "   Creando usuario...\n";
    $user = \App\Models\User::create([
        'name' => 'Admin SIFEN',
        'email' => 'admin@sifen.local',
        'password' => bcrypt('admin123'),
        'email_verified_at' => now(),
    ]);
    echo "   ✓ Usuario creado\n";
}
echo "\n";

// 3. Verificar auto-login config
echo "3. AUTO-LOGIN CONFIG:\n";
echo "   Enabled: " . (config('sifen.auto_login.enabled') ? 'SI' : 'NO') . "\n";
echo "   Email: " . config('sifen.auto_login.email') . "\n";
echo "\n";

// 4. Verificar rutas Livewire
echo "4. RUTAS LIVEWIRE:\n";
$routes = \Route::getRoutes();
$livewireRoutes = 0;
foreach ($routes as $route) {
    if (str_contains($route->uri(), 'livewire')) {
        $livewireRoutes++;
    }
}
echo "   Rutas Livewire encontradas: {$livewireRoutes}\n\n";

// 5. Verificar archivos Livewire
echo "5. ARCHIVOS LIVEWIRE:\n";
$livewireJs = public_path('vendor/livewire/livewire.js');
if (file_exists($livewireJs)) {
    echo "   ✓ livewire.js existe en: " . $livewireJs . "\n";
} else {
    echo "   ✗ livewire.js NO encontrado\n";
    echo "   Ejecutando: php artisan livewire:publish --assets\n";
    \Artisan::call('livewire:publish', ['--assets' => true, '--force' => true]);
    echo "   ✓ Assets publicados\n";
}
echo "\n";

// 6. Limpiar cachés
echo "6. LIMPIANDO CACHES:\n";
\Artisan::call('config:clear');
echo "   ✓ Config cache cleared\n";
\Artisan::call('view:clear');
echo "   ✓ View cache cleared\n";
\Artisan::call('route:clear');
echo "   ✓ Route cache cleared\n";
echo "\n";

echo "========================================\n";
echo "DIAGNOSTICO COMPLETADO\n";
echo "========================================\n\n";

echo "CREDENCIALES:\n";
echo "Email: admin@sifen.local\n";
echo "Password: admin123\n\n";

echo "ACCESO:\n";
echo "http://10.99.99.56:8080/portalsifen/admin\n\n";

echo "Si el auto-login no funciona, intenta:\n";
echo "1. Cerrar todas las pestañas del navegador\n";
echo "2. Abrir en modo incógnito\n";
echo "3. Acceder a la URL\n";
