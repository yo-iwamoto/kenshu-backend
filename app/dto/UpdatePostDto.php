<?php
namespace App\dto;

class UpdatePostDto
{
    public function __construct(
        public string $user_id,
        public string $title,
        public string $content,
        public array $tags,
    ) {
    }
}
