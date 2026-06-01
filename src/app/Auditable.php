<?php

namespace App;

use App\Actions\CreateAuditLogAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

trait Auditable
{
    /**
     * Boot the Auditable trait.
     * Laravel automatically calls this when the model boots.
     */
    public static function bootAuditable()
    {
        // REMOVED: parent::boot();

        static::created(function ($model) {
            if (Auth::id()) {
                Log::info('created: auditable');
                 CreateAuditLogAction::run(
                    tenantId: $model->tenant_id,
                    userId: Auth::id(),
                    event: 'created',
                    entityType: $model::class,
                    entityId: $model->id,
                    oldValues: null,
                    newValues: $model->toArray(),
                );
            }
        });

        static::updated(function ($model) {
            // Check if model is auditable and not currently being soft-deleted
            if (!isset($model->deleted_at) && Auth::id()) {
                CreateAuditLogAction::run(
                    tenantId: $model->tenant_id,
                    userId: Auth::id(),
                    event: 'updated',
                    entityType: $model::class,
                    entityId: $model->id,
                    oldValues: $model->getOriginal(),
                    newValues: $model->toArray(),
                );
            }
        });

        static::deleted(function ($model) {
            if (Auth::id()) {
                CreateAuditLogAction::run(
                    tenantId: $model->tenant_id,
                    userId: Auth::id(),
                    event: 'deleted',
                    entityType: $model::class,
                    entityId: $model->id,
                    oldValues: $model->getOriginal()
                );
            }
        });
    }
}