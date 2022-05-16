<?php
set_include_path('/var/www/html');
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::CreateImmutable('/var/www/html');
$dotenv->load();
