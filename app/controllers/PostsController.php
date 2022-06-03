<?php
namespace App\controllers;

use App\lib\Controller;
use App\lib\ServerException;
use App\models\Tag;
use App\services\post\CreateService;
use App\services\post\DestroyService;
use App\services\post\GetService;
use App\services\post\IndexService;
use App\services\post\UpdateService;

use Exception;

class PostsController extends Controller
{
    const VIEW_DIR = 'posts/';

    protected function index($request)
    {
        $service = new IndexService();
        $posts = $service->execute();

        $this->setData('posts', $posts);
    }

    protected function show($_, $id)
    {
        $service = new GetService();
        $post = $service->execute($id);
        

        $this->setData('post', $post);
    }

    protected function create($request)
    {
        try {
            $service = new CreateService();
            $post = $service->execute($request);

            $request->redirect("/posts/{$post->id}");
        } catch (Exception | ServerException $exception) {
            $this->setData('error_message', $exception instanceof ServerException ? $exception->display_text : '不明なエラーが発生しました');

            $index_service = new IndexService();
            $posts = $index_service->execute();
            $this->setData('posts', $posts);

            $this->view($request, self::VIEW_DIR, 'index');
        }
    }

    protected function edit($request, $id)
    {
        $service = new GetService();
        $post = $service->execute($id);
        $this->setData('post', $post);

        $tag_ids = array_map(fn (Tag $tag) => $tag->id, $post->tags);
        $this->setData('tag_ids', $tag_ids);
    }

    protected function update($request, $id)
    {
        try {
            $service = new UpdateService();
            $service->execute($request, $id);

            $request->redirect("/posts/$id/");
        } catch (Exception | ServerException $exception) {
            $this->setData('error_message', $exception instanceof ServerException ? $exception->display_text : '不明なエラーが発生しました');

            $get_service = new GetService();
            $post = $get_service->execute($id);
            $this->setData('post', $post);

            $tag_ids = array_map(fn (Tag $tag) => $tag->id, $post->tags);
            $this->setData('tag_ids', $tag_ids);

            $this->view($request, self::VIEW_DIR, 'edit');
        }
    }

    protected function destroy($request, $id)
    {
        $service = new DestroyService();
        $service->execute($id);

        $request->redirect('/posts/');
    }
}
