<?php
namespace App\dto;

class CreatePostDto
{
    public function __construct(
        public string $user_id,
        public string $title,
        public string $content,
        public array $tags,
    ) {
    }
}
