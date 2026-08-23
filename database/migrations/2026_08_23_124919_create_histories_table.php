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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->morphs('historable', 'idx_histories_on_historable');
            $table->json('data');
            $table->dateTime('fetched_at');
            $table->timestamps();
        });

        Schema::table('histories', function (Blueprint $table) {
            $table->index(['historable_type', 'historable_id', 'fetched_at'], 'idx_histories_on_historable_and_fetched_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
