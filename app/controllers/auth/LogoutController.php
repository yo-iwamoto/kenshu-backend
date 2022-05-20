<?php

namespace App\controllers\auth;

use App\lib\Controller;

class LogoutController extends Controller
{
    protected function post($request)
    {
        $request->unsetSession('user_id');

        return $request->redirect('/');
    }
}
