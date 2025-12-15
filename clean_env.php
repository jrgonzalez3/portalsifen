<?php

$path = __DIR__ . '/.env';
$lines = file($path, FILE_IGNORE_NEW_LINES);
$newLines = [];

foreach ($lines as $line) {
    // Skip comments or empty lines
    if (empty(trim($line)) || str_starts_with(trim($line), '#')) {
        $newLines[] = $line;
        continue;
    }

    // Parse KEY=VALUE
    if (preg_match('/^([^=]+)=(.*)$/', $line, $matches)) {
        $key = trim($matches[1]);
        $value = trim($matches[2]);

        // Remove trailing comma if exists at very end
        if (str_ends_with($value, ',')) {
            $value = substr($value, 0, -1);
        }
        
        // Remove surrounding quotes if they exist, to re-add clean ones later maybe?
        // Or just leave it if it looks valid.
        // The error was 'whitespace at ["0001",]' implies value was ' "0001",' (space quote value quote comma)
        
        // Trim spaces around value
        $value = trim($value);
        
        // Reconstruct line
        $newLines[] = "$key=$value";
    } else {
        $newLines[] = $line;
    }
}

file_put_contents($path, implode("\n", $newLines));
echo "Cleaned .env file.\n";
