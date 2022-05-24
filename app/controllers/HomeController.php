<?php
namespace App\controllers;

use App\lib\Request;
use App\lib\Controller;

class HomeController extends Controller
{
    const VIEW_DIR = '';

    protected function beforeAction(Request $request)
    {
        if ($request->isAuthenticated()) {
            $request->redirect('/posts');
        }
    }

    protected function index($_)
    {
    }
}
