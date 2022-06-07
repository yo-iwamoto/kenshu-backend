<?php
namespace App\controllers;

use App\lib\Controller;

class HomeController extends Controller
{
    const VIEW_DIR = '';

    protected function index($request)
    {
        $request->redirect('/posts/');
    }
}
