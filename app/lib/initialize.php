<?php

use Dotenv\Dotenv;

class Initializer
{
    public static function initialize()
    {
        self::setupAutoload();
        self::loadEnv();
    }

    private static function setupAutoload()
    {
        set_include_path('/var/www/html');
        require_once 'vendor/autoload.php';
    }

    private static function loadEnv()
    {
        Dotenv::CreateImmutable('/var/www/html')->load();
    }
}

Initializer::initialize();
