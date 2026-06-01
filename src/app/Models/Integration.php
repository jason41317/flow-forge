<?php

namespace App\Models;

use App\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Integration extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

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

}
