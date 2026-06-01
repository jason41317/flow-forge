<?php

namespace App\Http\Controllers\Api;

use App\Actions\LoginAction;
use App\Actions\RegisterAction;
use App\DTOs\LoginData;
use App\DTOs\RegisterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $dto = new RegisterData(
            name: $request->name,
            email: $request->email,
            password: $request->password,
            tenantName: $request->tenant_name
        );

        $user = RegisterAction::run($dto);

        return response()->json([
            'message' => 'Registered successfully',
            'user' => $user,
        ]);
    }

    public function login(LoginRequest $request)
    {
        $dto = new LoginData(
            email: $request->email,
            password: $request->password
        );

        return LoginAction::run($dto);
    }
}
