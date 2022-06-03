<?php
namespace App\services\concerns;

use App\lib\PDOFactory;
use App\lib\Request;

use PDO;

abstract class ServiceWithId extends Service
{
    protected PDO $pdo;
    
    public function __construct(
        protected Request $request,
        protected string $id,
    ) {
        $this->pdo = PDOFactory::create();
    }
}
