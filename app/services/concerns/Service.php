<?php
namespace App\services\concerns;

use App\lib\PDOFactory;

use PDO;

abstract class Service
{
    protected PDO $pdo;
    
    public function __construct(
    ) {
        $this->pdo = PDOFactory::create();
    }
}
