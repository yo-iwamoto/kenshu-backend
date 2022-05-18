<?php
set_include_path('/var/www/html');
require_once 'vendor/autoload.php';
require_once __DIR__ . './../lib/flash.php';

Dotenv\Dotenv::CreateImmutable('/var/www/html')->load();

$flash = Lib\Flash::get();
