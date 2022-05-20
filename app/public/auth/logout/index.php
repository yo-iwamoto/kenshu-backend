<?php
require_once '../../../lib/initialize.php';

use App\controllers\auth\LogoutController;

(new LogoutController())->handle();
