<?php
namespace App\services\concerns;

use App\lib\PDOFactory;
use App\lib\Request;

use PDO;

abstract class Service
{
    protected PDO $pdo;
    
    public function __construct(
        protected Request $request,
    ) {
        $this->pdo = PDOFactory::create();
    }
}
