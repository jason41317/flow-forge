<?php

namespace Database\Seeders;

use App\Models\LeadStatus;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class LeadStatusSeeder extends Seeder
{
    public function run(): void
    {
        Tenant::all()->each(function ($tenant) {

            collect([
                ['name' => 'New', 'color' => '#3B82F6'],
                ['name' => 'Contacted', 'color' => '#EAB308'],
                ['name' => 'Qualified', 'color' => '#22C55E'],
                ['name' => 'Won', 'color' => '#10B981'],
                ['name' => 'Lost', 'color' => '#EF4444'],
            ])->each(function ($status) use ($tenant) {

                LeadStatus::firstOrCreate([
                    'tenant_id' => $tenant->id,
                    'name' => $status['name'],
                ], [
                    'color' => $status['color'],
                ]);
            });
        });
    }
}
