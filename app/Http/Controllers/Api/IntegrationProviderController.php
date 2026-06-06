<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\IntegrationProviderResource;
use Illuminate\Http\Request;
use App\Models\IntegrationProvider;
use App\Support\Api\ApiResponse;

class IntegrationProviderController extends Controller
{
    public function index(Request $request)
    {
        $integrationProviders = IntegrationProvider::get();

        return ApiResponse::success(
            IntegrationProviderResource::collection($integrationProviders),
            'Integration providers fetched',
            200
        );
    }
}
