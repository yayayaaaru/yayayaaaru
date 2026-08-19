<?php

use App\Enums\GameAgeRating;
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
        Schema::table('games', function (Blueprint $table) {
            $table->string('age_rating', 6)->default(GameAgeRating::R0)->after('description');
            $table->unsignedTinyInteger('cis_rating')->default(0)->after('age_rating');
            $table->decimal('rating', 2, 1)->default(0.0)->after('cis_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('rating');
            $table->dropColumn('age_rating');
            $table->dropColumn('cis_rating');
        });
    }
};
