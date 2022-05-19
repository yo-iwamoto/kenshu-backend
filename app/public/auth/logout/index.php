<?php
require_once '../../../lib/initialize.php';

use App\lib\Helper;

unset($_SESSION['user_id']);

Helper::redirectTmp('/');
