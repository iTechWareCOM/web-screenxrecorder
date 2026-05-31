<?php
define('ROOT', dirname(__DIR__).DIRECTORY_SEPARATOR);
define('APP', ROOT.'App'.DIRECTORY_SEPARATOR);
define('CONTROLLER_FOLDER', APP.'Controller'.DIRECTORY_SEPARATOR);
define('PUBLIC_FOLDER', 'Public');
define('RESOURCES', ROOT.'Resources'.DIRECTORY_SEPARATOR);
define('DATA_LANG_FOLDER', RESOURCES.'Translate'.DIRECTORY_SEPARATOR);
define('VERSION_APP', '1.2026.05.31.1');
define('ENVIRONMENT', 'PRO');

$envFile = ROOT . '.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (substr(trim($line), 0, 1) === '#') continue;
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

include_once CONTROLLER_FOLDER . '/AppController.php';
$AppController = new AppController();
$AppController -> run();