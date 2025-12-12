<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "DIAGNOSTICO DE LOGIN - SIFEN PANEL\n";
echo "========================================\n\n";

// 1. Verificar rutas de Filament
echo "1. RUTAS FILAMENT:\n";
$routes = \Route::getRoutes();
$adminRoutes = [];
foreach ($routes as $route) {
    if (str_contains($route->uri(), 'admin')) {
        $adminRoutes[] = $route->uri() . ' => ' . $route->getName();
    }
}
echo "   Total rutas admin: " . count($adminRoutes) . "\n";
foreach (array_slice($adminRoutes, 0, 10) as $route) {
    echo "   - $route\n";
}
echo "\n";

// 2. Verificar usuarios en base de datos
echo "2. USUARIOS EN BASE DE DATOS:\n";
try {
    $users = \App\Models\User::all();
    echo "   Total usuarios: " . $users->count() . "\n";
    foreach ($users as $user) {
        echo "   - ID: {$user->id}, Email: {$user->email}, Nombre: {$user->name}\n";
    }
} catch (\Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}
echo "\n";

// 3. Verificar configuración de Filament
echo "3. CONFIGURACION FILAMENT:\n";
echo "   APP_URL: " . config('app.url') . "\n";
echo "   ASSET_URL: " . (config('app.asset_url') ?: 'No configurado') . "\n";
echo "   Filament Panels: " . (class_exists('Filament\Panel') ? 'Instalado' : 'NO instalado') . "\n";
echo "\n";

// 4. Verificar provider de Filament
echo "4. FILAMENT PANEL PROVIDER:\n";
$providerPath = app_path('Providers/Filament/AdminPanelProvider.php');
if (file_exists($providerPath)) {
    echo "   ✓ AdminPanelProvider existe\n";
} else {
    echo "   ✗ AdminPanelProvider NO existe\n";
}
echo "\n";

// 5. Probar autenticación manual
echo "5. PRUEBA DE AUTENTICACION:\n";
echo "   Ingresa las credenciales para probar:\n";
echo "   Email: ";
$email = trim(fgets(STDIN));
echo "   Password: ";
$password = trim(fgets(STDIN));

$user = \App\Models\User::where('email', $email)->first();
if ($user) {
    echo "\n   ✓ Usuario encontrado: {$user->name}\n";
    
    if (\Hash::check($password, $user->password)) {
        echo "   ✓ Password CORRECTO\n";
        echo "\n   RESULTADO: Las credenciales son válidas.\n";
        echo "   El problema puede ser:\n";
        echo "   - Rutas de Filament no configuradas correctamente\n";
        echo "   - Middleware bloqueando el acceso\n";
        echo "   - Sesiones no funcionando\n";
    } else {
        echo "   ✗ Password INCORRECTO\n";
        echo "\n   RESULTADO: La contraseña no coincide.\n";
        echo "   Solución: Resetear password con:\n";
        echo "   php reset-admin-password.php\n";
    }
} else {
    echo "\n   ✗ Usuario NO encontrado con email: $email\n";
    echo "\n   RESULTADO: El usuario no existe.\n";
    echo "   Solución: Crear usuario con:\n";
    echo "   php artisan make:filament-user\n";
}

echo "\n========================================\n";
echo "FIN DEL DIAGNOSTICO\n";
echo "========================================\n";
