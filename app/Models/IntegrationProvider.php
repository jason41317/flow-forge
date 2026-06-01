<?php

namespace App\Models;

use Database\Factories\IntegrationProviderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationProvider extends Model
{
    /** @use HasFactory<IntegrationProviderFactory> */
    use HasFactory;
}
