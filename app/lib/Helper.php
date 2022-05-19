<?php
namespace App\lib;

class Helper
{
    public static function redirectTmp(string $path)
    {
        header('Location: ' . $_ENV['APP_URL'] . $path, true, 302);
    }

    public static function setSession(string $key, string $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function unsetSession(string $key)
    {
        unsed($_SESSION[$key]);
    }
}
