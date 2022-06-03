<?php
namespace App\services\post;

use App\dto\UpdatePostDto;
use App\lib\Request;
use App\lib\ServerException;
use App\models\Post;
use App\models\PostToTag;
use App\services\concerns\Service;

use Exception;

class UpdateService extends Service
{
    public function execute(Request $request, string $id)
    {
        $pdo = $this->pdo;

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
}
