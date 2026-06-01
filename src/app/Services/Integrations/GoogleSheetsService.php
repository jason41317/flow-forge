<?php

namespace App\Services\Integrations;

use App\Models\Lead;

class GoogleSheetsService
{
    public function appendRow(
        string $spreadsheetId,
        string $sheetName,
        Lead $lead
    ): void {
        // pseudo logic (Google API later)

        logger()->info('Appending lead to sheet', [
            'spreadsheet' => $spreadsheetId,
            'sheet' => $sheetName,
            'lead_id' => $lead->id,
        ]);
    }
}
