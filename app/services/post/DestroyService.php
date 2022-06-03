<?php
namespace App\services\post;

use App\lib\PDOFactory;
use App\models\Post;

class DestroyService
{
    public static function execute(string $id)
    {
        $pdo = PDOFactory::create();

        $post = Post::getById($pdo, $id);

        $post->destroy($pdo);
    }
}
