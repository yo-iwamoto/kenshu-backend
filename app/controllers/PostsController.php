<?php
namespace App\controllers;

use App\lib\Controller;
use App\lib\ServerException;
use App\models\Post;
use App\models\Tag;
use App\services\AttachTagsService;
use Exception;

class PostsController extends Controller
{
    const VIEW_DIR = 'posts/';
    
    protected function index($_)
    {
        $this->addData('posts', Post::getAll());
    }

    protected function show($_, $id)
    {
        $post = Post::getById($id);
        $post->getTags();

        $this->addData('post', $post);
    }

    protected function create($request)
    {
        try {
            $current_user = $request->getCurrentUser();
            if ($current_user === null) {
                return http_response_code(403);
            }

            $post_id = Post::create(
                user_id: $current_user->id,
                title: $request->post['title'],
                content: $request->post['content'],
            );

            // tags が指定されていない場合 'tags' key が存在しないので、分岐
            if (isset($request->post['tags'])) {
                $post = Post::getById($post_id);
    
                AttachTagsService::execute($post, $request->post['tags']);
            }
            $request->redirect('/posts/');
        } catch (Exception $exception) {
            $this->addData('error_message', $exception instanceof ServerException ? $exception->display_text : '不明なエラーが発生しました');

            // TODO: 別の方法で対処 (一覧画面と新規作成画面が同一なので、エラー時にも posts を取得する必要がある問題)
            $this->addData('posts', Post::getAll());

            $this->view($request, self::VIEW_DIR, 'index');
        }
    }

    protected function edit($request, $id)
    {
        $post = Post::getById($id);
        $post->getTags();
        $tag_ids = array_map(function (Tag $tag) {
            return $tag->id;
        }, $post->tags);

        $this->addData('post', $post);
        $this->addData('tag_ids', $tag_ids);
    }

    protected function update($request, $id)
    {
        $post = Post::getById($id);
        $post->update(
            title: $request->post['title'],
            content: $request->post['content'],
        );

        // tags が指定されていない場合 'tags' key が存在しないので、分岐
        if (isset($request->post['tags'])) {
            $post->getTags();
    
            AttachTagsService::execute($post, $request->post['tags']);
        }
        $request->redirect("/posts/$id/");
    }

    protected function destroy($request, $id)
    {
        Post::getById($id)->destroy();

        $request->redirect('/posts/');
    }
}
