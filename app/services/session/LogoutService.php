<?php
namespace App\services\session;

use App\lib\Request;

class LogoutService
{
    public static function execute(Request $request)
    {
        $request->unsetSession('user_id');
    }
}
