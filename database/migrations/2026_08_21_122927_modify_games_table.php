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
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('rating');

            $table->unsignedTinyInteger('cis_rating')->nullable()->change();
            $table->integer('reviews_count')->unsigned()->nullable()->after('cis_rating');
            $table->json('reviews_scores_stat')->nullable()->after('reviews_count');
            $table->decimal('reviews_scores_avg', 4, 3)->unsigned()->nullable()->after('reviews_scores_stat');
            $table->decimal('min_load_time_seconds', 8,3)->unsigned()->nullable()->after('reviews_scores_avg');
            $table->json('tag_ids')->nullable()->after('min_load_time_seconds');
            $table->json('category_ids')->nullable()->after('tag_ids');

            $table->renameColumn('cis_rating', 'cis_score');
        });

        DB::table('games')->update(['cis_score' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('reviews_count');
            $table->dropColumn('reviews_scores_stat');
            $table->dropColumn('reviews_scores_avg');
            $table->dropColumn('min_load_time_seconds');
            $table->dropColumn('tag_ids');
            $table->dropColumn('category_ids');

            $table->renameColumn('cis_score', 'cis_rating');
            $table->decimal('rating', 2, 1)->default(0.0)->after('cis_rating');
        });
    }
};
