<?php
namespace App\services\user;

use App\models\User;
use App\services\concerns\ServiceWithId;

class GetService extends ServiceWithId
{
    public function execute()
    {
        $pdo = $this->pdo;
        $id = $this->id;

        return User::getById($pdo, $id);
    }
}
