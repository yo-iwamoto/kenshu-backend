<?php
namespace App\controllers;

use App\lib\Controller;

class LogoutController extends Controller
{
    const VIEW_DIR = 'logout/';

    protected function create($request)
    {
        $request->unsetSession('user_id');

        return $request->redirect('/');
    }
}
