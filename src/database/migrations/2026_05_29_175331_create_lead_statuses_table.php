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
        Schema::create('lead_statuses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');

            $table->string('color')
                ->nullable();

            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->boolean('is_default')
                ->default(false)
                ->nullable();

            $table->boolean('is_closed')
                ->default(false)
                ->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_statuses');
    }
};
