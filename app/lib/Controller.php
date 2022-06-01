<?php

namespace App\lib;

use App\lib\Request;
use App\models\User;
use App\views\ApplicationView;
use Closure;
use Exception;

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

        if ($request->method === 'POST' || $request->method === 'PUT') {
            self::validateCsrfToken($request);
        }

        // 静的なルート
        switch ([$request->method, $inner_path]) {
            case ['GET', '/']:
                $this->execAction(fn () => $this->index($request));
                $this->view($request, $this->view_dir, 'index');
                break;

            case ['GET', '/new']:
                $this->execAction(fn () => $this->new($request));
                $this->view($request, $this->view_dir, 'new');
                break;

            case ['POST', '/']:
                $this->execAction(fn () => $this->create($request));
                $this->view($request, $this->view_dir, 'index');
        }

        // id を含むルート
        $id = explode('/', $inner_path)[1];

        if (str_contains($inner_path, '/edit')) {
            $this->execAction(fn () => $this->edit($request, $id));
            $this->view($request, $this->view_dir, 'edit');
        }

        switch ($request->method) {
            case 'GET':
                $this->execAction(fn () => $this->show($request, $id));
                $this->view($request, $this->view_dir, 'show');
                break;

            case 'PUT':
                $this->execAction(fn () => $this->update($request, $id));
                $this->view($request, $this->view_dir, 'show');
                break;

            case 'DELETE':
                $this->execAction(fn () => $this->destroy($request, $id));
                $this->view($request, $this->view_dir, 'index');
        }
    }

    public static function validateCsrfToken(Request $request)
    {
        if ($request->getSession('csrf_token') !== $request->post['csrf_token']) {
            throw new Exception('csrf check failed');
        }
    }

    /**
     * callback を実行し、発生した例外を処理する
     */
    private function execAction(Closure $callback)
    {
        try {
            $callback();
        } catch (Exception $exception) {
            $this->handleException($exception);
        }
    }

    private function handleException(Exception $exception)
    {
        if ($exception instanceof ServerException) {
            if ($exception->display_text !== null) {
                $this->addData('error_message', $exception->display_text);
            } else {
                switch ($exception->name) {
                        case ServerExceptionName::NO_SUCH_RECORD:
                            $this->addData('error_message', 'データが存在していません');
                            break;
                        case ServerExceptionName::INTERNAL:
                            $this->addData('error_message', 'エラーが発生しました。時間をおいて再度お試しください');
                            break;
                        default:
                        $this->addData('error_message', '不明なエラーが発生しました');
                    }
            }
        } else {
            $this->addData('error_message', '不明なエラーが発生しました');
        }
    }
}
