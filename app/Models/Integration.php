<?php

namespace App\Models;

use App\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Integration extends Model
{
    use BelongsToTenant, HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'config' => 'array',
        'enabled' => 'boolean',
    ];

    public function provider() : BelongsTo
    {
        return $this->belongsTo(IntegrationProvider::class, 'integration_provider_id');
    }

    public function tenant() : BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function facebookForms() : HasMany
    {
        return $this->hasMany(
            FacebookForm::class
        );
    }

    public function scopeProviderFilter(
        Builder $query,
        string $code
    ): Builder {
        return $query->whereHas(
            'provider',
            fn ($query) => $query->where('code', $code)
        );
    }

    public function getConfig(string $key, mixed $default = null): mixed
    {
        return data_get($this->config, $key, $default);
    }

}
