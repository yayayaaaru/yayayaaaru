<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dateTime('released_at')->nullable()->change()->after('views_count');
            $table->longText('description')->nullable()->change()->after('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('games')->whereNull('released_at')->update(['released_at' => now()]);
        DB::table('games')->whereNull('description')->update(['description' => '']);

        Schema::table('games', function (Blueprint $table) {
            $table->dateTime('released_at')->nullable(false)->change();
            $table->longText('description')->nullable(false)->change();
        });
    }
};
