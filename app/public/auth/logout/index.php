<?php

use App\controllers\LogoutController;

require_once '../../../lib/initialize.php';

$controller = new LogoutController();
$controller->handle();
