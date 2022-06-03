<?php
namespace App\services;

use App\dto\CreatePostDto;
use App\dto\UpdatePostDto;
use App\lib\PDOFactory;
use App\lib\Request;
use App\models\Post;
use App\models\PostToTag;

class PostService
{
    public static function index()
    {
        $pdo = PDOFactory::create();

        return Post::getAll($pdo);
    }

    public static function get(string $id)
    {
        $pdo = PDOFactory::create();

        $post = Post::getById($pdo, $id);
        $post->getTags($pdo);

        return $post;
    }

    public static function create(Request $request)
    {
        $pdo = PDOFactory::create();

        $current_user_id = $request->getCurrentUserId();

        try {
            $dto = new CreatePostDto(
                user_id: $current_user_id,
                title: $request->post['title'],
                content: $request->post['content'],
                tags: isset($request->post['tags']) ? $request->post['tags'] : [],
            );

            $pdo->beginTransaction();

            $post_id = Post::create($pdo, $dto);

            $post = Post::getById($pdo, $post_id);

            // TODO: O(n) の回避
            foreach ($dto->tags as $tag_id) {
                PostToTag::create($pdo, $post->id, $tag_id);
            }

            $pdo->commit();

            $post->getTags($pdo);

            return $post;
        } catch (Exception | ServerException $exception) {
            $pdo->rollBack();

            throw $exception;
        }
    }

    public static function update(Request $request, string $id)
    {
        $pdo = PDOFactory::create();

        $current_user_id = $request->getCurrentUserId();

        try {
            $dto = new UpdatePostDto(
                user_id: $current_user_id,
                title: $request->post['title'],
                content: $request->post['content'],
                tags: isset($request->post['tags']) ? $request->post['tags'] : [],
            );

            $pdo->beginTransaction();

            $post = Post::getById($pdo, $id);
            $post->update($pdo, $dto);
            $post->getTags($pdo);

            $current_tag_ids = [];

            // TODO: 以下、計算量が本当に酷いので修正
            foreach ($post->tags as $tag) {
                if (!in_array($tag->id, $dto->tags)) {
                    PostToTag::destroy($pdo, $id, $tag->id);
                } else {
                    array_push($current_tag_ids, $tag->id);
                }
            }
            // 指定された tag_ids のうち、現在存在していないものを追加
            foreach (array_diff($dto->tags, $current_tag_ids) as $tag_id) {
                PostToTag::create($pdo, $id, $tag_id);
            }

            $pdo->commit();

            $post->getTags($pdo);

            return $post;
        } catch (Exception | ServerException $exception) {
            $pdo->rollBack();

            throw $exception;
        }
    }

    public static function destroy(string $id)
    {
        $pdo = PDOFactory::create();

        $post = Post::getById($pdo, $id);

        $post->destroy($pdo);
    }
}
