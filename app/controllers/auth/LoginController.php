<?php

namespace App\controllers\auth;

use App\lib\Controller;
use App\models\User;

class LoginController extends Controller
{
    const VIEW_DIR = 'auth/login/';

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
        $user = User::get_by_email($request->post['email']);
        if ($user->login($request->post['password'])) {
            $request->setSession('user_id', $user->id);

            return $request->redirect('/');
        } else {
            // TODO: エラーのフィードバック
            $this->view(self::VIEW_DIR . 'get.php');
        }
    }
}
