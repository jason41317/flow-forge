<?php

namespace App\Models;

use App\Auditable;
use App\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadStatus extends Model
{
    use Auditable, BelongsToTenant, HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
