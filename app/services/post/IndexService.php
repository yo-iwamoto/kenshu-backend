<?php
namespace App\services\post;

use App\models\Post;
use App\services\concerns\Service;

class IndexService extends Service
{
    public function execute()
    {
        $pdo = $this->pdo;

        return Post::getAll($pdo);
    }
}
