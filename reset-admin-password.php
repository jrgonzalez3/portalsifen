<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

// Buscar usuario
$user = User::where('email', 'admin@sifen.local')->first();

if ($user) {
    // Actualizar contraseña
    $user->password = bcrypt('admin123');
    $user->save();
    echo "✅ Contraseña actualizada exitosamente para: {$user->email}\n";
    echo "Email: admin@sifen.local\n";
    echo "Password: admin123\n";
} else {
    // Crear usuario nuevo
    $user = User::create([
        'name' => 'Admin SIFEN',
        'email' => 'admin@sifen.local',
        'password' => bcrypt('admin123'),
        'email_verified_at' => now(),
    ]);
    echo "✅ Usuario creado exitosamente\n";
    echo "Email: admin@sifen.local\n";
    echo "Password: admin123\n";
}
