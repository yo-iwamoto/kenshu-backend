<?php

use App\lib\Request;
use App\lib\Router;
use Dotenv\Dotenv;

use App\controllers\HomeController;
use App\controllers\LogoutController;
use App\controllers\PostsController;
use App\controllers\SessionsController;
use App\controllers\UsersController;

class Initializer
{
    public static function initialize()
    {
        self::setupAutoload();
        self::loadEnv();
        self::resolveRoute();
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

    private static function resolveRoute()
    {
        $router = new Router(routes: array(
            '/' => function () {
                return new HomeController('');
            },

            '/sessions' => function () {
                return new SessionsController('sessions/');
            },

            '/users' => function () {
                return new UsersController('users/');
            },

            '/logout' => function () {
                return new LogoutController('logout/');
            }
        ));

        $router->resolve(request: new Request());
    }
}

Initializer::initialize();
