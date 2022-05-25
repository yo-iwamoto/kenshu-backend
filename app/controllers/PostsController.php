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

    protected function create($request)
    {
        $current_user = $request->getCurrentUser();
        if ($current_user === null) {
            return http_response_code(403);
        }
        
        Post::create(
            user_id: $current_user->id,
            title: $request->post['title'],
            content: $request->post['content'],
        );

        $request->redirect('/posts');
    }

    protected function edit($request, $id)
    {
        $post = Post::getById($id);

        $this->addData('post', $post);
    }

    protected function update($request, $id)
    {
        Post::getById($id)->update(
            title: $request->post['title'],
            content: $request->post['content'],
        );
        
        $request->redirect("/posts/$id/");
    }

    protected function destroy($request, $id)
    {
        Post::getById($id)->destroy();

        $request->redirect('/posts/');
    }
}
