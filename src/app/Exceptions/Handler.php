<?php

namespace App\Exceptions;

use App\Support\Api\ApiResponse;
use Exception;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends Exception
{
    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return ApiResponse::error(
                'Validation failed',
                $e->errors(),
                422
            );
        }

        return ApiResponse::error(
            $e->getMessage() ?: 'Server Error',
            null,
            500
        );
    }
}
