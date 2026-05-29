<?php

namespace App\Actions;

use App\DTOs\LoginData;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginAction
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function run(LoginData $data): array
    {
        if (!Auth::attempt([
            'email' => $data->email,
            'password' => $data->password
        ])) {
            abort(401, 'Invalid credentials');
        }

        $user = User::where('email', $data->email)->first();

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

}
