<?php

namespace App\lib;

use App\lib\Request;

/**
 * コントローラーのベースクラス
 * 必要に応じて、preHandle、get等を定義し、handle を呼ぶとメソッドによって処理を行う。
 * @todo 統一されたインターフェースでビューに値を渡す
 */
abstract class Controller
{
    protected Request $request;
    
    public function __construct(
    ) {
        $this->request = new Request();
    }

    protected function isLoggedIn()
    {
        return isset($this->request->session['user_id']);
    }

    protected function getUserId()
    {
        return $this->isLoggedIn() ? $this->request->session['user_id'] : null;
    }

    protected function preHandle()
    {
    }
    protected function get()
    {
    }
    protected function post()
    {
    }
    protected function patch()
    {
    }
    protected function put()
    {
    }
    protected function destroy()
    {
    }

    public function handle()
    {
        $this->preHandle();
        
        switch ($this->request->method) {
            case 'GET':
                $this->get();
                break;
            case 'POST':
                $this->post();
                break;
            case 'PATCH':
                $this->patch();
                break;
            case 'PUT':
                $this->put();
                break;
            case 'DELETE':
                $this->destroy();
                break;
            default:
                // TODO: 404
        }
    }
}
