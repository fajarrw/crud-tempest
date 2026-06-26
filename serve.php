<?php

require_once __DIR__ . '/vendor/autoload.php';

// Muat variabel dari file .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$port = $_ENV['APP_PORT'] ?? 8000;

passthru("php tempest serve --port={$port}");