<?php
namespace App\services\session;

use App\lib\Request;
use App\lib\ServerException;
use App\models\User;
use App\services\concerns\Service;

use Exception;

class LoginService extends Service
{
    public function execute(Request $request)
    {
        $pdo = $this->pdo;

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
}
