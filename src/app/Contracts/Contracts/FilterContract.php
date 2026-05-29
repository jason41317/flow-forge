<?php

namespace App\Contracts\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface FilterContract
{
    public function apply(
        Builder $query,
        Request $request,
        string $key
    ): Builder;

}
