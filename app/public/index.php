<?php
require_once '../lib/initialize.php';

use App\controllers\HomeController;

(new HomeController())->handle();
