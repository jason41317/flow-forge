<?php

namespace App\Actions;

use App\Models\LeadStatus;

class DeleteLeadStatusAction
{
    /**
     * Create a new class instance.
     */
    public static function run(LeadStatus $leadStatus)
    {
        $leadStatus->delete();

        //events
    }
}
