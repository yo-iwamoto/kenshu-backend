<?php
namespace App\lib;

use App\lib\Request;

class Router
{
    public function __construct(
        private array $routes
    ) {
    }

    public function resolve(Request $request)
    {
        foreach ($this->routes as $path => $createControllerInstance) {
            if ($request->path === $path) {
                $controller = $createControllerInstance();
                $controller->handle($request, inner_path: '/');
                break;
            }


            if ($path !== '/' && str_contains($request->path, $path)) {
                // ベースパスと trailing slash を取り除く ex) /posts/10/ -> /10
                $inner_path = str_replace($path, '', $request->path, );
                if ($inner_path !== '/' && substr($inner_path, -1) === '/') {
                    $inner_path = substr($inner_path, 0, -1);
                }

                $controller = $createControllerInstance();
                $controller->handle($request, $inner_path);
                break;
            }
        }

        // 一致するパスがなかった場合
        return http_response_code(404);
    }
}
