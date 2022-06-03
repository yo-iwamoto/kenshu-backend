<?php
namespace App\services\post;

use App\models\Post;
use App\services\concerns\Service;

class DestroyService extends Service
{
    public function execute(string $id)
    {
        $pdo = $this->pdo;

        $post = Post::getById($pdo, $id);

        $post->destroy($pdo);
    }
}
