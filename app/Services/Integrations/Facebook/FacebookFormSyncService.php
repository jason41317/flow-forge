<?php

namespace App\Services\Integrations\Facebook;

use App\Models\FacebookForm;
use App\Models\Integration;
use Illuminate\Support\Facades\Http;

class FacebookFormSyncService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function sync(
        Integration $integration
    ): void {

        $response = Http::get(
            "https://graph.facebook.com/v23.0/{$integration->getConfig('page_id')}/leadgen_forms",
            [
                'access_token' => $integration->getConfig('access_token'),
            ]
        );

        foreach ($response->json('data', []) as $form) {
            FacebookForm::updateOrCreate(
                [
                    'facebook_form_id' => $form['id'],
                ],
                [
                    'integration_id' => $integration->id,
                    'name' => $form['name'],
                ]
            );
        }
    }
}
