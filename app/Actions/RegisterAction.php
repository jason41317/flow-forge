<?php

namespace App\Actions;

use App\DTOs\RegisterData;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterAction
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function run(RegisterData $data): User
    {
        $tenant = Tenant::create([
            'name' => $data->tenantName,
            'slug' => Str::slug($data->tenantName).'-'.rand(1000, 9999),
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);

        return $user;
    }
}
