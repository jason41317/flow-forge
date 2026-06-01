<?php

namespace App\Models;

use App\BelongsToTenant;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'source',
        'type',
        'lead_status_id',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
    ];

    public function fieldValues()
    {
        return $this->hasMany(LeadFieldValue::class);
    }

    public function status()
    {
        return $this->belongsTo(LeadStatus::class);
    }
}
