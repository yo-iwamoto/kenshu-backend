<?php

namespace App\lib;

use App\lib\Request;

abstract class Controller
{
    protected Request $request;
    
    public function __construct(
    ) {
        $this->request = new Request();
    }

    protected function isLoggedIn()
    {
        return isset($this->request->session['user_id']);
    }

    protected function getUserId()
    {
        return $this->isLoggedIn() ? $this->request->session['user_id'] : null;
    }
}
