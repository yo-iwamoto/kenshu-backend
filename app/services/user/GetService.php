<?php
namespace App\services\user;

use App\models\User;
use App\services\concerns\Service;

class GetService extends Service
{
    public function execute(string $id)
    {
        $pdo = $this->pdo;

        return User::getById($pdo, $id);
    }
}
