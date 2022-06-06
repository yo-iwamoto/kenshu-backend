<?php

namespace App\lib;

use App\lib\Request;
use App\views\ApplicationView;

use Closure;
use Exception;

abstract class Controller
{
    const VIEW_BASE_DIR = 'app/views/';

    // テンプレートに渡される値
    protected array $data = [];

    protected function setData(string $key, mixed $value)
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
        $this->setData('is_authenticated', $is_authenticated);
        $this->setData('current_user', $is_authenticated ? $request->getCurrentUser() : null);

        // CSRF token の生成・セット
        $csrf_token = bin2hex(random_bytes(32));
        $request->setSession('csrf_token', $csrf_token);
        $this->setData('csrf_token', $csrf_token);

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

        if ($request->method === 'POST' || $request->method === 'PUT' || $request->method === 'DELETE') {
            $this->validateCsrfToken($request);
        }

        // 静的なルート
        switch ([$request->method, $inner_path]) {
            case ['GET', '/']:
                $this->execAction(
                    $request,
                    callback: fn () => $this->index($request),
                    template: 'index',
                );
                break;

            case ['GET', '/new']:
                $this->execAction(
                    $request,
                    callback: fn () => $this->new($request),
                    template: 'new',
                );
                break;

            case ['POST', '/']:
                $this->execAction(
                    $request,
                    callback: fn () => $this->create($request),
                    template: 'index',
                    error_template: 'new',
                );
        }

        // id を含むルート
        $id = explode('/', $inner_path)[1];

        if (str_contains($inner_path, '/edit')) {
            $this->execAction(
                $request,
                callback: fn () => $this->edit($request, $id),
                template: 'edit',
            );
        }

        switch ($request->method) {
            case 'GET':
                $this->execAction(
                    $request,
                    callback: fn () => $this->show($request, $id),
                    template: 'show',
                );
                break;

            case 'PUT':
                $this->execAction(
                    $request,
                    callback: fn () => $this->update($request, $id),
                    template: 'show',
                    error_template: 'edit',
                );
                break;

            case 'DELETE':
                $this->execAction(
                    $request,
                    callback: fn () => $this->destroy($request, $id),
                    template: 'index',
                    error_template: 'index'
                );
        }
    }

    public function validateCsrfToken(Request $request)
    {
        if ($request->getSession('csrf_token') !== $request->post['csrf_token']) {
            $this->setData('http_referer', $request->server['HTTP_REFERER']);
            $this->view($request, 'utils/', 'csrf_error');
        }
    }

    /**
     * callback を実行し、発生した例外を処理する
     */
    private function execAction(Request $request, Closure $callback, string $template, ?string $error_template = null)
    {
        try {
            $callback();

            $this->view($request, $this->view_dir, $template);
        } catch (Exception | ServerException $exception) {
            $this->setData('error_message', $exception instanceof ServerException ? $exception->display_text : '不明なエラーが発生しました');

            $this->view($request, $this->view_dir, $error_template !== null ? $error_template : $template);
        }
    }
}
