<?php
declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$file = __DIR__ . $path;

if ($path !== '/' && is_file($file)) {
    return false;
}

if ($path === '/admin' || str_starts_with($path, '/admin/')) {
    $_GET['path'] = trim(substr($path, strlen('/admin')), '/');
    require __DIR__ . '/admin/index.php';
    return true;
}

require __DIR__ . '/index.php';
return true;
