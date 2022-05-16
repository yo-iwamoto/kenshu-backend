<?php
require_once dirname(__FILE__, 2) . '/config/env.php';

// TODO: 環境の switch
$pdo = new PDO(
    "pgsql:host=db;dbname={$_ENV['POSTGRES_DEV_DATABASE']};",
    $_ENV['POSTGRES_DEV_USER'],
    $_ENV['POSTGRES_DEV_PASSWORD'],
);
