<?php
set_include_path('/var/www/html');
require_once 'vendor/autoload.php';

Dotenv\Dotenv::CreateImmutable('/var/www/html')->load();
