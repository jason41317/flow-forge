<?php

namespace App\DTOs;

class LoginData
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
