<?php
namespace App\services\session;

use App\lib\Request;
use App\services\concerns\Service;

class LogoutService extends Service
{
    public function execute(Request $request)
    {
        $request = $this->request;

        $request->unsetSession('user_id');
    }
}
