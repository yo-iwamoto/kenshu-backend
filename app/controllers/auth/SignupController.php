<?php

namespace App\controllers\auth;

use App\lib\Controller;
use App\services\SignupService;

class SignupController extends Controller
{
    const VIEW_DIR = 'auth/signup/';
    
    protected function preHandle($request)
    {
        if ($request->isAuthenticated()) {
            return $request->redirect('/');
        }
    }

    protected function get($request)
    {
        $this->view(self::VIEW_DIR . 'get.php');
    }
    
    protected function post($request)
    {
        try {
            SignupService::execute($request);

            $this->view(self::VIEW_DIR . 'post.php');
        } catch (Exception $err) { // TODO: エラーの種類を拾う
            // TODO: 登録フォームを保ったままエラーを表示
            // TODO: 既にユーザーが存在する時、ログインページへ遷移
            $this->view(self::VIEW_DIR . 'get.php');
            print_r($err);
        }
    }
}
