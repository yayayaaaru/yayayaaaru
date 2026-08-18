<?php

declare(strict_types=1);

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
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->morphs('sourceable', 'idx_sources_on_sourceable');
            $table->string('name');
            $table->string('external_id');
            $table->timestamps();
        });

        Schema::table('sources', function (Blueprint $table) {
            $table->unique(['name', 'external_id'], 'unq_sources_on_name_and_external_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sources');
    }
};
