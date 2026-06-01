<?php

namespace App\Http\Controllers\Api;

use App\Actions\CreateLeadAction;
use App\Actions\UpdateLeadAction;
use App\DTOs\LeadData;
use App\Filters\LeadFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Resources\LeadResource;
use App\Models\Lead;
use App\Support\Api\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LeadController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, LeadFilter $filter)
    {
        Gate::authorize('viewAny', Lead::class);

        $query = Lead::query();

        $query = $filter->apply($query, $request);

        return ApiResponse::success(
            LeadResource::collection($query->paginate(10)),
            'Leads fetched'
        );
    }

    public function store(StoreLeadRequest $request)
    {
        Gate::authorize('create', Lead::class);

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
        );

        $lead = CreateLeadAction::run($dto);

        return ApiResponse::success(
            new LeadResource($lead),
            'Lead created',
            201
        );
    }

    public function update(Lead $lead, StoreLeadRequest $request)
    {
        Gate::authorize('update', $lead);

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

            customFields: $this->sanitizeCustomFields($request->custom_fields ?? [])
        );

        $lead = UpdateLeadAction::run($lead, $dto);

        return ApiResponse::success(
            new LeadResource($lead),
            'Lead updated',
            200
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
        Gate::authorize('view', $lead);

        return response()->json([
            'data' => $lead,
        ]);
    }
}
