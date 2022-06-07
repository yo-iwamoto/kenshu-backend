<?php
namespace App\controllers;

use App\lib\Controller;
use App\services\session\LoginService;
use App\services\session\LogoutService;

class SessionsController extends Controller
{
    const VIEW_DIR = 'sessions/';

    protected function new($request)
    {
        // ログイン状態の時、記事一覧画面へリダイレクト
        if ($request->isAuthenticated()) {
            return $request->redirect('/posts');
        }
    }

    protected function create($request)
    {
        // ログイン状態の時、記事一覧画面へリダイレクト
        if ($request->isAuthenticated()) {
            return $request->redirect('/posts');
        }

        $service = new LoginService();
        $service->execute($request);

        // 記事一覧画面へリダイレクト
        return $request->redirect('/posts');
    }

    protected function destroy($request, $_)
    {
        $service = new LogoutService();
        $service->execute($request);

        return $request->redirect('/');
    }
}
