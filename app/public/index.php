<?php

use App\lib\Request;
use App\lib\Router;
use Dotenv\Dotenv;

use App\controllers\HomeController;
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

            '/posts' => function () {
                return new PostsController('posts/');
            },

            '/sessions' => function () {
                return new SessionsController('sessions/');
            },

            '/users' => function () {
                return new UsersController('users/');
            },
        ));

        $request = new Request();

        // クエリを見て $request->method　を更新することで、手動で PUT と DELETE に対応
        if (isset($request->post['_method'])) {
            if ($request->post['_method'] === 'PUT') {
                $request->updateMethodManually('PUT');
            } elseif ($request->post['_method'] === 'DELETE') {
                $request->updateMethodManually('DELETE');
            }
        }

        $router->resolve($request);
    }
}

Initializer::initialize();
