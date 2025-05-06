<?php
require __DIR__ . '/vendor/autoload.php';

// Ładowanie zmiennych środowiskowych z pliku .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Weryfikacja wymaganych zmiennych środowiskowych
$dotenv->required([
    'MYSQL_HOST', 'MYSQL_USER', 'MYSQL_PASS', 'MYSQL_DB',
    'REDIS_HOST', 'REDIS_PORT', 'REDIS_DB',
    'SEARCH_PREFIX'
]);