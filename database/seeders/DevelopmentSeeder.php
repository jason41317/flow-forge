<?php

namespace Database\Seeders;

use App\Models\FacebookForm;
use App\Models\Integration;
use App\Models\IntegrationFieldMapping;
use App\Models\IntegrationProvider;
use App\Models\Lead;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::firstOrCreate([
            'name' => 'Demo Tenant',
            'slug' => 'demo-tenant',
        ]);

        $user = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant->id,
        ]);

        $facebookProvider = IntegrationProvider::where(
            'code',
            'facebook'
        )->first();

        $integration = Integration::firstOrCreate([
            'tenant_id' => $tenant->id,
            'integration_provider_id' => $facebookProvider->id,
            'name' => 'Demo Facebook Page',
        ], [
            'enabled' => true,
            'config' => [
                'page_id' => '123456789',
                'page_name' => 'Demo Facebook Page',
                'access_token' => 'fake-token',
            ],
        ]);

        $buyForm = FacebookForm::firstOrCreate([
            'facebook_form_id' => 'fb-buy-house',
        ], [
            'integration_id' => $integration->id,
            'name' => 'Buy House Form',
        ]);

        $sellForm = FacebookForm::firstOrCreate([
            'facebook_form_id' => 'fb-sell-house',
        ], [
            'integration_id' => $integration->id,
            'name' => 'Sell House Form',
        ]);

        $this->seedMappings($buyForm);

        Lead::factory()
            ->count(20)
            ->create([
                'tenant_id' => $tenant->id,
            ]);
    }

    private function seedMappings(
        FacebookForm $form
    ): void {

        $mappings = [
            [
                'source_field' => 'Email',
                'target_type' => 'lead',
                'target_value' => 'email',
            ],
            [
                'source_field' => 'Phone Number',
                'target_type' => 'lead',
                'target_value' => 'phone',
            ],
            [
                'source_field' => 'Budget',
                'target_type' => 'custom_field',
                'target_value' => 'budget',
            ],
        ];

        foreach ($mappings as $mapping) {

            IntegrationFieldMapping::updateOrCreate(
                [
                    'facebook_form_id' => $form->id,
                    'source_field' => $mapping['source_field'],
                ],
                $mapping
            );
        }
    }
}
