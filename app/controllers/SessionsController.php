<?php
namespace App\controllers;

use App\lib\Controller;
use App\models\User;

class SessionsController extends Controller
{
    const VIEW_DIR = 'sessions/';

    protected function beforeAction($request)
    {
        // ログイン状態の時、記事一覧画面へリダイレクト
        if ($request->isAuthenticated()) {
            return $request->redirect('/posts');
        }
    }

    protected function new($_)
    {
        // TODO: CSRF token の生成、データ格納
    }

    protected function create($request)
    {
        // TODO: CSRF token の検証

        // TODO: バリデーション

        $user = User::getByEmail($request->post['email']);
        if ($user->login($request->post['password'])) {
            $request->setSession('user_id', $user->id);

            return $request->redirect('/');
        } else {
            // TODO: エラーのフィードバック
            $this->view($request, self::VIEW_DIR, 'new');
        }


        // 記事一覧画面へリダイレクト
        return $request->redirect('/posts');
    }
}
