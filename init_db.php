<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$schema = env('DB_SCHEMA', 'public');
echo "Target Schema: $schema\n";

try {
    DB::statement("CREATE SCHEMA IF NOT EXISTS \"$schema\"");
    echo "Schema created successfully.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
