<?php
namespace App\controllers;

use App\lib\Controller;
use App\lib\ServerException;
use App\lib\ServerExceptionName;
use App\models\User;
use Exception;

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
    }

    protected function create($request)
    {
        try {
            $user = User::getByEmail($request->post['email']);
            if ($user->login($request->post['password'])) {
                $request->setSession('user_id', $user->id);
    
                return $request->redirect('/');
            } else {
                throw ServerException::invalidRequest(display_text: 'ログインに失敗しました。再度お試しください');
            }
    
    
            // 記事一覧画面へリダイレクト
            return $request->redirect('/posts');
        } catch (Exception $exception) {
            if ($exception instanceof ServerException && $exception->name === ServerExceptionName::NO_SUCH_RECORD) {
                $exception->display_text = 'ログインに失敗しました。再度お試しください';
            }

            throw $exception;
        }
    }

    protected function destroy($request, $_)
    {
        $request->unsetSession('user_id');

        return $request->redirect('/');
    }
}
