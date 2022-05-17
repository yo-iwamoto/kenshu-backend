<?php
require_once '../config/initialize.php';
require_once '../models/user.php';

$user = User::get_by_email('you.iwamotddo@prtimes.co.jp');

print_r($user);
