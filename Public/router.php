<?php
// Router for PHP built-in server — replicates Public/.htaccess behavior
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Strip leading slash and pass as url param (mirrors RewriteRule)
$_GET['url'] = ltrim($uri, '/');

require __DIR__ . '/index.php';
