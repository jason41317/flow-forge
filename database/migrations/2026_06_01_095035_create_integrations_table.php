<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('integration_provider_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            // "Sales Sheet", "Marketing Sheet", "Main HubSpot"

            $table->json('config')->nullable();
            /*
                Example for Google Sheets:
                {
                    "spreadsheet_id": "xxx",
                    "sheet_name": "Leads"
                }

                Example for Facebook:
                {
                    "page_id": "xxx",
                    "access_token": "xxx"
                }
            */

            $table->boolean('enabled')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // prevents duplicate same provider per tenant instance name if needed
            $table->unique(['tenant_id', 'integration_provider_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};
