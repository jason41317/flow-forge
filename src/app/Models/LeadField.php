<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadField extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'key',
        'type',
        'is_required',
    ];

}
