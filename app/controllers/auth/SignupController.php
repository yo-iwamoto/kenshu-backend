<?php

namespace App\controllers\auth;

use Ramsey\Uuid\Nonstandard\Uuid;

use App\lib\Controller;
use App\models\User;

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
        $uploaded_file_path = null;

        if ($request->files['profile_image']['tmp_name'] !== '') {
            $uploaded_file_path = '/assets/img/users/' . Uuid::uuid4() . '_' . $request->files['profile_image']['name'];
        }

        $profile_image_url = $uploaded_file_path !== null ? $uploaded_file_path : '/assets/img/default-icon.jpg';

        try {
            User::create(
                email: $request->post['email'],
                name: $request->post['name'],
                password: $request->post['password'],
                profile_image_url: $profile_image_url,
            );

            if ($uploaded_file_path) {
                move_uploaded_file($request->files['profile_image']['tmp_name'], '../..' . $file_path);
            }

            $user = User::get_by_email($request->post['email']);

            $request->setSession('user_id', $user->id);

            $this->view(self::VIEW_DIR . 'post.php');
        } catch (Exception $err) { // TODO: エラーの種類を拾う
            // TODO: 登録フォームを保ったままエラーを表示
            // TODO: 既にユーザーが存在する時、ログインページへ遷移
            $this->view(self::VIEW_DIR . 'get.php');
            print_r($err);
        }
    }
}
