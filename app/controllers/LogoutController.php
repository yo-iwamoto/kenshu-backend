<?php

namespace App\controllers;

use App\lib\Controller;
use App\lib\Helper;

class LogoutController extends Controller
{
    protected function post()
    {
        Helper::unsetSession('user_id');

        Helper::redirectTmp('/');
    }
}
