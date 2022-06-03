<?php
namespace App\services\post;

use App\models\Post;
use App\services\concerns\ServiceWithId;

class GetService extends ServiceWithId
{
    public function execute()
    {
        $pdo = $this->pdo;
        $id = $this->id;

        $post = Post::getById($pdo, $id);
        $post->getTags($pdo);

        return $post;
    }
}
