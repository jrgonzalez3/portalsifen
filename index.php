<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

// Redirect to public folder
if (file_exists(__DIR__ . '/public/index.php')) {
    header('Location: /public/');
    exit;
}

echo 'Laravel installation error: public/index.php not found';
