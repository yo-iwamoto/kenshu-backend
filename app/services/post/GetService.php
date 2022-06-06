<?php
namespace App\services\post;

use App\models\Post;
use App\services\concerns\Service;

class GetService extends Service
{
    public function execute(string $id)
    {
        $pdo = $this->pdo;

        $post = Post::getById($pdo, $id);
        $post->getTags($pdo);
        $post->getImages($pdo);

        return $post;
    }
}
