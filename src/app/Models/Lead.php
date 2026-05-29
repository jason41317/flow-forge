<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'tenant_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'source',
        'status',
        'type',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
    ];

    protected static function booted()
    {
        static::addGlobalScope('tenant', function ($query) {
            if ($tenant = app('tenant')) {
                $query->where('tenant_id', $tenant->id);
            }
        });
    }

    public function fieldValues()
    {
        return $this->hasMany(LeadFieldValue::class);
    }
}
