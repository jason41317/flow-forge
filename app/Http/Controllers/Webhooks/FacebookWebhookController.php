<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Jobs\Integrations\ImportFacebookLeadJob;
use Illuminate\Http\Request;

class FacebookWebhookController extends Controller
{
    public function verify(Request $request)
    {
        if (
            $request->input('hub_verify_token')
            !== config('services.facebook.verify_token')
        ) {
            abort(403);
        }

        return response(
            $request->input('hub_challenge'),
            200
        );
    }

    public function handle(Request $request)
    {
        foreach ($request->input('entry', []) as $entry) {

            foreach ($entry['changes'] ?? [] as $change) {

                $leadgenId = data_get(
                    $change,
                    'value.leadgen_id'
                );

                $pageId = data_get(
                    $change,
                    'value.page_id'
                );

                $formId = data_get(
                    $change,
                    'value.form_id'
                );

                if (! $leadgenId) {
                    continue;
                }

                ImportFacebookLeadJob::dispatch(
                    $leadgenId,
                    $pageId,
                    $formId
                );
            }
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
