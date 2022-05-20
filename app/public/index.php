<?php
require_once '../lib/initialize.php';

use App\controllers\RootController;

(new RootController())->handle();
