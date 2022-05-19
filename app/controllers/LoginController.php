<?php

namespace App\controllers;

use App\lib\Controller;
use App\lib\Helper;
use App\models\User;

class LoginController extends Controller
{
    public function handle()
    {
        if ($this->getUserId() !== null) {
            Helper::redirectTmp(path: '/');
            return;
        }

        switch ($this->request->server['REQUEST_METHOD']) {
            case 'GET':
                $this->new();
                break;

            case 'POST':
                $this->login();
                break;
        }
    }

    private function new()
    {
    }
    
    private function login()
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
