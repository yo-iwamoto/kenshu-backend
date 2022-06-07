<?php
namespace App\controllers;

use App\lib\Controller;
use App\lib\ServerException;
use App\lib\ServerExceptionName;
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
            // デフォルトでは create の例外に対して /new を表示するため、index を指定

            $this->setData('error_message', $exception instanceof ServerException ? $exception->display_text : '不明なエラーが発生しました');

            $index_service = new IndexService();
            $posts = $index_service->execute();
            $this->setData('posts', $posts);

            $this->view($request, self::VIEW_DIR, 'index');
        }
    }

    protected function edit($request, $id)
    {
        try {
            $current_user_id = $request->getCurrentUserId();

            $service = new GetService();
            $post = $service->execute($id);
            if ($post->user__id !== $current_user_id) {
                throw ServerException::unauthorized();
            }

            $this->setData('post', $post);

            $tag_ids = array_map(fn (Tag $tag) => $tag->id, $post->tags);
            $this->setData('tag_ids', $tag_ids);
        } catch (ServerException $exception) {
            // 他のユーザーの記事の編集画面にアクセスを試行した際、記事一覧へ遷移
            if ($exception->name === ServerExceptionName::UNAUTHORIZED) {
                $this->setData('error_message', $exception->display_text);

                // view に必要なデータの取得
                $index_service = new IndexService();
                $posts = $index_service->execute();
                $this->setData('posts', $posts);
    
                $this->view($request, self::VIEW_DIR, 'index');
            }

            // Controller->execAction が拾う
            throw $exception;
        }
    }

    protected function update($request, $id)
    {
        try {
            $current_user_id = $request->getCurrentUserId();
            $get_service = new GetService();
            $post = $get_service->execute($id);
            if ($post->user__id !== $current_user_id) {
                throw ServerException::unauthorized();
            }
            
            $service = new UpdateService();
            $service->execute($request, $id);

            $request->redirect("/posts/$id/");
        } catch (ServerException $_) {
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
        $service->execute($request, $id);

        $request->redirect('/posts/');
    }
}
