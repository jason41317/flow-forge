<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadFieldValue extends Model
{
    protected $fillable = [
        'tenant_id',
        'lead_id',
        'lead_field_id',
        'value',
    ];
}
