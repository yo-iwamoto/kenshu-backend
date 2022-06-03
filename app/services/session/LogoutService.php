<?php
namespace App\services\session;

use App\services\concerns\Service;

class LogoutService extends Service
{
    public function execute()
    {
        $request = $this->request;

        $request->unsetSession('user_id');
    }
}
