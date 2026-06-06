<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\IntegrationResource;
use App\Models\Integration;
use App\Support\Api\ApiResponse;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function index(Request $request)
    {
        $provider = $request->provider ?? '';
        $integrations = Integration::providerFilter($provider)
            ->select([
                'id',
                'name',
                'enabled',
                'created_at',
            ])
            ->get();

        return ApiResponse::success(
            IntegrationResource::collection($integrations),
            'Integrations fetched',
            200
        );
    }

}
