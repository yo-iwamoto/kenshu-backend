<?php

namespace App\lib;

use App\lib\Request;
use App\models\User;

/**
 * コントローラーのベースクラス
 * 必要に応じて、preHandle、get等を定義し、handle を呼ぶとメソッドによって処理を行う。
 * @todo 統一されたインターフェースでビューに値を渡す
 */
abstract class Controller
{
    const VIEW_BASE_DIR = 'app/views/';
    
    protected Request $request;
    
    public function __construct(
    ) {
        $this->request = new Request();
    }

    protected function preHandle(Request $request)
    {
    }

    protected function get(Request $request)
    {
        return http_response_code(404);
    }
    protected function post(Request $request)
    {
        return http_response_code(404);
    }
    protected function patch(Request $request)
    {
        return http_response_code(404);
    }
    protected function put(Request $request)
    {
        return http_response_code(404);
    }
    protected function destroy(Request $request)
    {
        return http_response_code(404);
    }

    public function handle()
    {
        $this->preHandle($this->request);
        
        switch ($this->request->method) {
            case 'GET':
                $this->get(request: $this->request);
                break;
            case 'POST':
                $this->post(request: $this->request);
                break;
            case 'PATCH':
                $this->patch(request: $this->request);
                break;
            case 'PUT':
                $this->put(request: $this->request);
                break;
            case 'DELETE':
                $this->destroy(request: $this->request);
                break;
            default:
                return http_response_code(404);
        }
    }

    public function view(string $path, array $data = [])
    {
        // 受け取ったデータを変数宣言
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        // 共通で利用する変数
        $is_authenticated = $this->request->isAuthenticated();
        $user = $is_authenticated ? User::get_by_id($this->request->getSession('user_id')) : null;
        
        $file_path = self::VIEW_BASE_DIR . $path;

        ob_start();
        require_once $file_path;
        $content = ob_get_clean();

        require_once('app/views/application.php');
        // TODO: データの展開
    }
}
