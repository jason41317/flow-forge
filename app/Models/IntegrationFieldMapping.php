<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntegrationFieldMapping extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function form(): BelongsTo
    {
        return $this->belongsTo(
            FacebookForm::class,
            'facebook_form_id'
        );
    }
}
