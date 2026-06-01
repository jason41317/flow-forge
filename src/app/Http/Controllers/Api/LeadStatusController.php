<?php

namespace App\Http\Controllers\Api;

use App\Actions\CreateLeadStatusAction;
use App\Actions\DeleteLeadStatusAction;
use App\Actions\UpdateLeadStatusAction;
use App\DTOs\LeadStatusData;
use App\Filters\LeadStatusFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadStatusRequest;
use App\Http\Resources\LeadStatusResource;
use App\Models\LeadStatus;
use App\Support\Api\ApiResponse;
use App\Support\Tenant\TenantManager;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LeadStatusController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, LeadStatusFilter $filter)
    {
        Gate::authorize('viewAny', LeadStatus::class);

        $query = LeadStatus::query();

        $query = $filter->apply($query, $request);

        return ApiResponse::success(
            LeadStatusResource::collection($query->paginate(10)),
            'Lead statuses fetched'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeadStatusRequest $request)
    {
        Gate::authorize('create', LeadStatus::class);

        $tenant = app(TenantManager::class)->get();

        $dto = new LeadStatusData(
            name: $request->name,
            color: $request->color,
            isDefault: $request->is_default,
            isClosed: $request->is_closed,

            tenantId: $tenant->id
        );

        $lead = CreateLeadStatusAction::run($dto);

        return ApiResponse::success(
            new LeadStatusResource($lead),
            'Lead Status created',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $leadStatus = LeadStatus::findOrFail($id);
        Gate::authorize('view', $leadStatus);

        return response()->json([
            'data' => $leadStatus,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $leadStatus = LeadStatus::findOrFail($id);
        Gate::authorize('update', $leadStatus);

        $tenant = app(TenantManager::class)->get();

        $dto = new LeadStatusData(
            name: $request->name,
            color: $request->color,
            isDefault: $request->is_default,
            isClosed: $request->is_closed,

            tenantId: $tenant->id
        );

        $leadStatus = UpdateLeadStatusAction::run($leadStatus, $dto);

        return ApiResponse::success(
            new LeadStatusResource($leadStatus),
            'Lead updated',
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $leadStatus = LeadStatus::findOrFail($id);
        Gate::authorize('delete', $leadStatus);

        DeleteLeadStatusAction::run($leadStatus);

        return ApiResponse::success(
            null,
            'Lead deleted',
            204
        );
    }
}
