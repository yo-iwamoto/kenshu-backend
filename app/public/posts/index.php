<?php
require_once '../../lib/initialize.php';

use App\controllers\PostsController;

(new PostsController())->handle();
