<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Tempest\Router\HttpApplication;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

HttpApplication::boot(__DIR__ . '/../')->run();

exit();
