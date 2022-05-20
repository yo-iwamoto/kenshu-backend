<?php
require_once '../../../lib/initialize.php';

use App\controllers\auth\SignupController;

(new SignupController())->handle();
