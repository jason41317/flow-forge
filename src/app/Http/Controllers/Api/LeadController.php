<?php

namespace App\Http\Controllers\Api;

use App\Actions\CreateLeadAction;
use App\DTOs\LeadData;
use App\Filters\LeadFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Resources\LeadResource;
use App\Models\Lead;
use App\Support\Api\ApiResponse;
use App\Support\Tenant\TenantManager;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, LeadFilter $filter)
    {
        $this->authorize('viewAny', Lead::class);

        $query = Lead::query();

        $query = $filter->apply($query, $request);

        return ApiResponse::success(
            LeadResource::collection($query->paginate(10)),
            'Leads fetched'
        );
    }

    public function store(StoreLeadRequest $request)
    {
        $this->authorize('create', Lead::class);

        $tenant = app(TenantManager::class)->get();

        $dto = new LeadData(
            firstName: $request->first_name,
            lastName: $request->last_name,
            email: $request->email,
            phone: $request->phone,
            source: $request->source,
            type: $request->type,

            utmSource: $request->utm_source,
            utmMedium: $request->utm_medium,
            utmCampaign: $request->utm_campaign,
            utmTerm: $request->utm_term,
            utmContent: $request->utm_content,

            customFields: $this->sanitizeCustomFields($request->custom_fields ?? []),

            tenantId: $tenant->id
        );

        $lead = CreateLeadAction::run($dto);

        return ApiResponse::success(
            new LeadResource($lead),
            'Lead created',
            201
        );
    }

    private function sanitizeCustomFields(array $fields): array
    {
        // remove dangerous keys or invalid structures
        return collect($fields)
            ->filter(fn ($value, $key) => is_string($key))
            ->toArray();
    }

    public function show(Lead $lead)
    {
        return response()->json([
            'data' => $lead,
        ]);
    }
}
