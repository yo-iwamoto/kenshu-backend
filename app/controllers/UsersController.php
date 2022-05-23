<?php
namespace App\controllers;

use App\lib\Controller;
use App\services\SignupService;

class UsersController extends Controller
{
    const VIEW_DIR = 'users/';

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

        SignupService::execute($request);

        // 記事一覧画面へリダイレクト
        $request->redirect('/posts');
    }
}
