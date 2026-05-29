<?php

namespace App\DTOs;

class RegisterData
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $tenantName,)
    {
        
    }
}
