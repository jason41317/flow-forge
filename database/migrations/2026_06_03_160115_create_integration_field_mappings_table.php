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
        Schema::create('integration_field_mappings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('facebook_form_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('source_field');

            $table->enum('target_type', [
                'lead',
                'custom_field',
            ]);

            $table->string('target_value');

            $table->timestamps();
            $table->softDeletes();

            $table->unique([
                'facebook_form_id',
                'source_field',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integration_field_mappings');
    }
};
