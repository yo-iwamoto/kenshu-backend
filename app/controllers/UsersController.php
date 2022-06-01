<?php
namespace App\controllers;

use App\lib\Controller;
use App\lib\ServerException;
use App\lib\ServerExceptionName;
use App\services\SignupService;
use Exception;

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
    }

    protected function create($request)
    {
        try {
            SignupService::execute($request);

            // 記事一覧画面へリダイレクト
            $request->redirect('/posts');
        } catch (Exception $exception) {
            if ($exception instanceof ServerException && $exception->name === ServerExceptionName::EMAIL_ALREADY_EXISTS) {
                $this->addData('error_message', '指定したメールアドレスは既に利用されています。ログインするか、違うメールアドレスで再度お試しください');
            }

            $this->view($request, self::VIEW_DIR, 'new');
        }
    }
}
