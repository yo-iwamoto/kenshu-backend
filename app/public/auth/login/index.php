<?php
require_once '../../../lib/initialize.php';

use App\controllers\auth\LoginController;

(new LoginController())->handle();
