<?php
namespace App\services\post;

use App\lib\PDOFactory;
use App\models\Post;

class GetService
{
    public static function execute(string $id)
    {
        $pdo = PDOFactory::create();

        $post = Post::getById($pdo, $id);
        $post->getTags($pdo);

        return $post;
    }
}
