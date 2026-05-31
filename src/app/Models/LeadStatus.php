<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::addGlobalScope(new TenantScope);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
