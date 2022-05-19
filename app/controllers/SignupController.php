<?php

namespace App\controllers;

use Ramsey\Uuid\Nonstandard\Uuid;

use App\lib\Controller;
use App\lib\Helper;
use App\models\User;

class SignupController extends Controller
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
                $this->signup();
                break;
        }
    }
    
    private function new()
    {
    }
    
    private function signup()
    {
        $file_path = '';
    
        if (isset($this->request->files['profile_image'])) {
            $file_path = '/assets/img/users/' . Uuid::uuid4() . '_' . $this->request->files['profile_image']['name'];
        }

        try {
            User::create(
                email: $this->request->post['email'],
                name: $this->request->post['name'],
                password: $this->request->post['password'],
                profile_image_url: $file_path,
            );

            move_uploaded_file($this->request->files['profile_image']['tmp_name'], '../..' . $file_path);

            $user = User::get_by_email($this->request->post['email']);

            Helper::setSession('user_id', $user->id);
        } catch (Exception $err) { // TODO: エラーの種類を拾う
            // TODO: 登録フォームを保ったままエラーを表示
            // TODO: 既にユーザーが存在する時、ログインページへ遷移
            print_r($err);
        }
    }
}
