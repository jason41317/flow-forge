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
        Schema::create('facebook_forms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('integration_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('facebook_form_id')->unique();

            $table->string('name');

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_forms');
    }
};
