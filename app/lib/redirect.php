<?php
function redirect_tmp(string $path)
{
    header('Location: ' . $_ENV['APP_URL'] . $path, true, 302);
}
