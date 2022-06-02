<?php
namespace App\services;

use App\lib\PDOFactory;
use App\lib\Request;
use App\lib\ServerException;
use App\models\User;

use Exception;

class SessionService
{
    public static function login(Request $request)
    {
        $pdo = PDOFactory::create();

        try {
            $user = User::getByEmail($pdo, $request->post['email']);
            if (!$user->login($request->post['password'])) {
                throw ServerException::invalidRequest(display_text: 'メールアドレスかパスワードが誤っています');
            }

            $request->setSession('user_id', $user->id);
        } catch (Exception | ServerException $exception) {
            if ($exception instanceof ServerException && $exception->name === ServerExceptionName::NO_SUCH_RECORD) {
                $exception->display_text = 'ログインに失敗しました。再度お試しください';
            }

            throw $exception;
        }
    }

    public static function logout(Request $request)
    {
        $request->unsetSession('user_id');
    }
}
