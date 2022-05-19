<?php

namespace App\controllers;

use App\lib\Controller;
use App\lib\Helper;
use App\models\User;

class LoginController extends Controller
{
    protected function preHandle()
    {
        if ($this->getUserId() !== null) {
            Helper::redirectTmp(path: '/');
            return;
        }
    }

    protected function get()
    {
    }
    
    protected function post()
    {
        $user = User::get_by_email($this->request->post['email']);
        if ($user->login($this->request->post['password'])) {
            Helper::setSession('user_id', $user->id);

            Helper::redirectTmp(path: '/');
            return;
        } else {
            // TODO: エラーのフィードバック
        }
    }
}
