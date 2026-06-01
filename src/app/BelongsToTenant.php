<?php

namespace App;

use App\Models\Scopes\TenantScope;
use App\Support\Tenant\TenantManager;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            $tenant = app(TenantManager::class)->get();
            if (
                empty($model->tenant_id)
                && $tenant
            ) {
                $model->tenant_id = $tenant->id;
            }
        });
    }
}
