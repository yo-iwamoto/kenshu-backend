<?php
namespace App\controllers;

use App\lib\Controller;
use App\lib\ServerException;
use App\models\Tag;
use App\services\PostService;

use Exception;

class PostsController extends Controller
{
    const VIEW_DIR = 'posts/';

    protected function index($request)
    {
        $posts = PostService::index();

        $this->setData('posts', $posts);
    }

    protected function show($_, $id)
    {
        $post = PostService::get($id);

        $this->setData('post', $post);
    }

    protected function create($request)
    {
        try {
            $post = PostService::create($request);

            $request->redirect("/posts/{$post->id}");
        } catch (Exception | ServerException $exception) {
            $this->setData('error_message', $exception instanceof ServerException ? $exception->display_text : '不明なエラーが発生しました');

            // TODO: 別の方法で対処 (一覧画面と新規作成画面が同一なので、エラー時にも posts を取得する必要がある問題)
            $posts = PostService::index();
            $this->setData('posts', $posts);

            $this->view($request, self::VIEW_DIR, 'index');
        }
    }

    protected function edit($request, $id)
    {
        $post = PostService::get($id);
        $this->setData('post', $post);

        $tag_ids = array_map(fn (Tag $tag) => $tag->id, $post->tags);
        $this->setData('tag_ids', $tag_ids);
    }

    protected function update($request, $id)
    {
        try {
            PostService::update($request, $id);

            $request->redirect("/posts/$id/");
        } catch (Exception | ServerException $exception) {
            $this->setData('error_message', $exception instanceof ServerException ? $exception->display_text : '不明なエラーが発生しました');

            // TODO: 別の方法で対処 (一覧画面と新規作成画面が同一なので、エラー時にも posts を取得する必要がある問題)
            $post = PostService::get($id);
            $this->setData('post', $post);

            $tag_ids = array_map(fn (Tag $tag) => $tag->id, $post->tags);
            $this->setData('tag_ids', $tag_ids);

            $this->view($request, self::VIEW_DIR, 'edit');
        }
    }

    protected function destroy($request, $id)
    {
        PostService::destroy($id);

        $request->redirect('/posts/');
    }
}
