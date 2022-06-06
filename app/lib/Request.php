<?php

namespace App\lib;

use App\models\User;
use App\services\user\GetService as UserGetService;

/**
 * グローバル定数から Request オブジェクトを作成する
 */
class Request
{
    public readonly array $server;
    public readonly ?array $get;
    public readonly ?array $post;
    public readonly ?array $files;
    public readonly ?array $request;

    public string $method;
    public readonly string $path;

    public function __construct(
    ) {
        $this->server = $_SERVER;
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->request = $_REQUEST;

        $this->method = $this->server['REQUEST_METHOD'];
        $this->path = $this->server['REQUEST_URI'];
    }

    public function getSession(string $key): string
    {
        return $_SESSION[$key];
    }

    public function setSession(string $key, string $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function unsetSession(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * PUT と DELETE を利用するためやむなく生やしている。
     */
    public function updateMethodManually(string $method)
    {
        $this->method = $method;
    }

    /**
     * `$_SESSION` に `user_id` があるかどうか
     */
    public function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * @return string $_SESSION['user_id']
     * @throws ServerException
     */
    public function getCurrentUserId(): string
    {
        if (!$this->isAuthenticated()) {
            throw ServerException::unauthorized(display_text: 'ログインしてください');
        }
        
        return $this->getSession('user_id');
    }

    /**
     * @return User
     * @throws ServerException
     */
    public function getCurrentUser(): User
    {
        if (!$this->isAuthenticated()) {
            throw ServerException::unauthorized(display_text: 'ログインしてください');
        }
        
        $service = new UserGetService();
        return $service->execute($this->getSession('user_id'));
    }

    public function redirect(string $path, int $status_code = 302): void
    {
        header('Location: ' . $_ENV['APP_URL'] . $path, true, $status_code);
    }
}
