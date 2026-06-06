<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FacebookForm extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function integration() : BelongsTo
    {
        return $this->belongsTo(
            Integration::class
        );
    }

    public function mappings() : HasMany
    {
        return $this->hasMany(
            IntegrationFieldMapping::class
        );
    }
}
