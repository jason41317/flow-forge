<?php

namespace App\Jobs\Integrations;

use App\Models\Integration;
use App\Models\Lead;
use App\Services\Integrations\GoogleSheetsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GoogleSheetsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Lead $lead,
        public Integration $integration
    )
    {
    
    }

    /**
     * Execute the job.
     */
    public function handle(GoogleSheetsService $service): void
    {
        $config = $this->integration->config;

        $service->appendRow(
            spreadsheetId: $config['spreadsheet_id'],
            sheetName: $config['sheet_name'],
            lead: $this->lead
        );
    }
}
