<?php

use Tempest\Database\Config\MysqlConfig;

return new MysqlConfig(
    host: $_ENV['DB_HOST'],
    port: (int) $_ENV['DB_PORT'],
    username: $_ENV['DB_USER'],
    password: $_ENV['DB_PASSWORD'],
    database: $_ENV['DB_NAME'],
);
