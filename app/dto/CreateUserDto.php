<?php
namespace App\dto;

class CreateUserDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $profile_image_url,
    ) {
    }
}
