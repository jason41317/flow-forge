<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadFieldValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'lead_id',
        'lead_field_id',
        'value',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new TenantScope);
    }

    public function field()
    {
        return $this->belongsTo(LeadField::class, 'lead_field_id');
    }
}
