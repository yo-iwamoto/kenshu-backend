<?php
namespace App\services\post;

use App\lib\PDOFactory;
use App\models\Post;

class IndexService
{
    public static function execute()
    {
        $pdo = PDOFactory::create();

        return Post::getAll($pdo);
    }
}
