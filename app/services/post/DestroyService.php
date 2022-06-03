<?php
namespace App\services\post;

use App\models\Post;
use App\services\concerns\ServiceWithId;

class DestroyService extends ServiceWithId
{
    public function execute()
    {
        $pdo = $this->pdo;
        $id = $this->id;

        $post = Post::getById($pdo, $id);

        $post->destroy($pdo);
    }
}
