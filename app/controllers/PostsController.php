<?php
namespace App\controllers;

use App\lib\Controller;
use App\models\Post;

class PostsController extends Controller
{
    const VIEW_DIR = 'posts/';
    
    protected function get($request)
    {
        $posts = Post::getAll();
        
        $this->view(self::VIEW_DIR . 'get.php', data: array('posts' => $posts));
    }
}
