<?php

namespace App\lib;

class Request
{
    public readonly array $server;
    public readonly ?array $get;
    public readonly ?array $post;
    public readonly ?array $files;
    public readonly ?array $session;
    public readonly ?array $request;

    public readonly string $method;

    public function __construct(
    ) {
        $this->server = $_SERVER;
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->session = $_SESSION;
        $this->request = $_REQUEST;

        $this->method = $this->server['REQUEST_METHOD'];
    }
}
