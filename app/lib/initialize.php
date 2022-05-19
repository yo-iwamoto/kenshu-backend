<?php
use Dotenv\Dotenv;

// autoload
set_include_path('/var/www/html');
require_once 'vendor/autoload.php';

Dotenv::CreateImmutable('/var/www/html')->load();
