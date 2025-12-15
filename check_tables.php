<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    $names = array_map(fn($t) => $t->table_name, $tables);
    echo "Tables found in public schema:\n";
    print_r($names);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
