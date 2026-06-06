<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFacebookFormMappingsRequest;
use App\Http\Resources\FacebookFormResource;
use App\Http\Resources\IntegrationFieldMappingResource;
use App\Models\FacebookForm;
use App\Models\Integration;
use App\Services\Integrations\Facebook\FacebookFormSyncService;
use App\Support\Api\ApiResponse;
use Illuminate\Http\Request;

class FacebookFormController extends Controller
{
    public function index()
    {
        $forms = FacebookForm::with('integration:id,name')
            ->get();

        return ApiResponse::success(
            FacebookFormResource::collection($forms),
            'Forms fetched'
        );
    }

    public function show(int $id)
    {
        $form = FacebookForm::findOrFail($id);

        return ApiResponse::success(
            new FacebookFormResource($form->load('integration')),
            'Form fetched',
            200
        );
    }

    public function mappings(int $id)
    {
        $form = FacebookForm::findOrFail($id);
        return ApiResponse::success(
            IntegrationFieldMappingResource::collection($form->mappings),
            'Mappings fetched',
            200
        );
    }

    public function updateMappings(UpdateFacebookFormMappingsRequest $request, int $id)
    {
        $form = FacebookForm::findOrFail($id);
        $form->mappings()->delete();

        foreach ($request->mappings as $mapping) {

            $form->mappings()->create([
                'source_field' => $mapping['source_field'],
                'target_type' => $mapping['target_type'],
                'target_value' => $mapping['target_value'],
            ]);
        }

        return ApiResponse::success(
            IntegrationFieldMappingResource::collection($form->mappings),
            'Mappings updated',
            200
        );
    }

    public function sync(FacebookFormSyncService $service, int $integrationId)
    {
        $integration = Integration::findOrFail($integrationId);
        $service->sync($integration);

        return ApiResponse::success(
            null,
            'Forms synced.',
            200
        );
    }
}
