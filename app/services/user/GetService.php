<?php
namespace App\services\user;

use App\lib\PDOFactory;
use App\models\User;

class GetService
{
    public static function execute(string $id)
    {
        $pdo = PDOFactory::create();

        return User::getById($pdo, $id);
    }
}
