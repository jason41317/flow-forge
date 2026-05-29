<?php

namespace App\Http\Controllers\Api;

use App\Actions\CreateLeadAction;
use App\DTOs\LeadData;
use App\Filters\LeadFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request, LeadFilter $filter)
    {
        $query = Lead::query();

        $query = $filter->apply($query, $request);

        return $query->paginate(10);
    }

    public function store(StoreLeadRequest $request)
    {
        $tenant = app('tenant');

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

        return response()->json([
            'data' => $lead,
        ], 201);
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
