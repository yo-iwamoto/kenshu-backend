<?php
namespace Lib;

class Flash
{
    private function __construct(
        public string $message,
        public string $type,
    ) {
    }

    public static function register(string $message, string $type = 'info')
    {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }

    public static function get()
    {
        if (isset($_SESSION['flash_message']) && isset($_SESSION['flash_type'])) {
            $instance = new Flash(
                message: $_SESSION['flash_message'],
                type: $_SESSION['flash_type'],
            );
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
            return $instance;
        } else {
            return null;
        }
    }
}
