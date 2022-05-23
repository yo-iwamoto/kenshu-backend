<?php
namespace App\controllers;

use App\lib\Controller;
use App\models\Post;

class PostsController extends Controller
{
    const VIEW_DIR = 'posts/';
    
    protected function index($_)
    {
        $posts = Post::getAll();

        $this->addData('posts', $posts);
    }

    protected function show($_, $id)
    {
        $post = Post::getById($id);

        $this->addData('post', $post);
    }
}
