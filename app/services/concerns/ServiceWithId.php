<?php
namespace App\services\concerns;

use App\lib\Request;

abstract class ServiceWithId extends Service
{
    public function __construct(
        protected Request $request,
        protected string $id,
    ) {
        parent::__construct($request);
    }
}
