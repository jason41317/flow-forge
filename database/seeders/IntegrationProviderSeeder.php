<?php

namespace Database\Seeders;

use App\Models\IntegrationProvider;
use Illuminate\Database\Seeder;

class IntegrationProviderSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            [
                'code' => 'facebook',
                'name' => 'Facebook Leads',
            ],
            [
                'code' => 'google_sheets',
                'name' => 'Google Sheets',
            ],
            [
                'code' => 'webhook',
                'name' => 'Webhook',
            ],
            [
                'code' => 'email',
                'name' => 'Email',
            ],
        ])->each(function ($provider) {
            IntegrationProvider::updateOrCreate(
                ['code' => $provider['code']],
                $provider
            );
        });
    }
}