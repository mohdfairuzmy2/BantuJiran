<?php

// Suppress Broken pipe & other notices from PHP built-in server
error_reporting(0);
ini_set('display_errors', '0');

$publicPath = __DIR__.'/public';

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

$filePath = $publicPath . $uri;

if ($uri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    // Serve static files from public/ with correct MIME type
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $mimes = [
        'js'   => 'application/javascript',
        'css'  => 'text/css',
        'html' => 'text/html',
        'json' => 'application/json',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'ico'  => 'image/x-icon',
        'svg'  => 'image/svg+xml',
        'woff2'=> 'font/woff2',
        'woff' => 'font/woff',
    ];
    if (isset($mimes[$ext])) {
        header('Content-Type: ' . $mimes[$ext]);
    }
    header('Cache-Control: public, max-age=86400');
    readfile($filePath);
    return;
}

require_once $publicPath . '/index.php';
