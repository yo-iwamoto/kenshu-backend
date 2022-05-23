<?php

namespace App\lib;

use App\lib\Request;
use App\models\User;
use App\views\ApplicationView;

abstract class Controller
{
    const VIEW_BASE_DIR = 'app/views/';

    // テンプレートに渡される値
    protected array $data = [];

    protected function addData(string $key, mixed $value)
    {
        $this->data = array_merge($this->data, array($key => $value));
    }

    public function __construct(private string $view_dir)
    {
    }

    private function logAndReturn404(Request $request)
    {
        error_log("[{$request->method}] {$request->path}");
        return http_response_code(404);
    }

    protected function beforeAction(Request $request)
    {
    }

    protected function index(Request $request)
    {
        $this->logAndReturn404($request);
    }
    protected function new(Request $request)
    {
        $this->logAndReturn404($request);
    }
    protected function create(Request $request)
    {
        $this->logAndReturn404($request);
    }
    protected function show(Request $request, string $id)
    {
        $this->logAndReturn404($request);
    }
    protected function edit(Request $request, string $id)
    {
        $this->logAndReturn404($request);
    }
    protected function update(Request $request, string $id)
    {
        $this->logAndReturn404($request);
    }
    protected function destroy(Request $request, string $id)
    {
        $this->logAndReturn404($request);
    }

    protected function view(Request $request, string $dir, string $name)
    {
        $is_authenticated = $request->isAuthenticated();
        $this->addData('is_authenticated', $is_authenticated);
        $this->addData('current_user', $is_authenticated ? User::getById($request->getSession('user_id')) : null);


        // CSRF token の生成・セット
        $csrf_token = bin2hex(random_bytes(32));
        $request->setSession('csrf_token', $csrf_token);
        $this->addData('csrf_token', $csrf_token);

        $data  = $this->data;

        ob_start();
        require_once self::VIEW_BASE_DIR . $dir . $name . '.php';
        $content = ob_get_clean();

        ApplicationView::render($content, $data);
        exit();
    }

    // リクエストに従って処理を行い、デフォルトのビューを返す。ビューはコールバック内で view を呼んで上書き可能。
    public function handle(Request $request, string $inner_path)
    {
        $this->beforeAction($request);

        // 静的なルート
        switch ([$request->method, $inner_path]) {
            case ['GET', '/']:
                $this->index($request);
                $this->view($request, $this->view_dir, 'index');
                break;

            case ['GET', '/new']:
                $this->new($request);
                $this->view($request, $this->view_dir, 'new');
                break;

            case ['POST', '/']:
                $this->create($request);
                $this->view($request, $this->view_dir, 'index');
        }

        // id を含むルート
        $id = explode('/', $inner_path)[1];

        if (str_contains($inner_path, '/edit')) {
            $this->edit($request, $id);
            $this->view($request, $this->view_dir, 'edit');
        }

        switch ($request->method) {
            case 'GET':
                $this->show($request, $id);
                $this->view($request, $this->view_dir, 'show');
                break;

            case 'PUT':
                $this->update($request, $id);
                $this->view($request, $this->view_dir, 'show');
                break;

            case 'DELETE':
                $this->destroy($request, $id);
                $this->view($request, $this->view_dir, 'index');
        }
    }
}
