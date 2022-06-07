<?php
namespace App\services\post;

use App\lib\Request;
use App\lib\ServerException;
use App\models\Post;
use App\services\concerns\Service;

class DestroyService extends Service
{
    public function execute(Request $request, string $id)
    {
        $pdo = $this->pdo;

        $post = Post::getById($pdo, $id);

        $current_user_id = $request->getCurrentUserId();
        // 記事の作成者か確認
        if ($post->user__id !== $current_user_id) {
            throw ServerException::unauthorized();
        }

        $post->destroy($pdo);
    }
}
