<?php

namespace App\Models;

use App\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Integration extends Model
{
    use BelongsToTenant, HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'config' => 'array',
        'enabled' => 'boolean',
    ];

    public function provider()
    {
        return $this->belongsTo(IntegrationProvider::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
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
