<?php
namespace App\controllers;

use App\lib\Controller;

class RootController extends Controller
{
    protected function get($request)
    {
        // 記事の取得
        $posts = [];
        
        $this->view('get.php');
    }
}
