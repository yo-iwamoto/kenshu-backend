<?php
require_once '../../../config/initialize.php';
require_once '../../../lib/redirect.php';
require_once '../../../lib/flash.php';

unset($_SESSION['user_id']);

Lib\Flash::register(message: 'ログアウトしました');

header('Location: http://localhost:2222');
