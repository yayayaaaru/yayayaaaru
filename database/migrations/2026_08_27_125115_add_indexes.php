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
            $table->index('released_at', 'idx_games_on_released_at');
        });

        Schema::table('game_category', function (Blueprint $table) {
            $table->primary(['game_id', 'category_id'], 'pk_game_category_on_game_id_and_category_id');
            $table->index('category_id', 'idx_game_category_on_category_id');
        });

        Schema::table('game_tag', function (Blueprint $table) {
            $table->primary(['game_id', 'tag_id'], 'pk_game_tag_on_game_id_and_tag_id');
            $table->index('tag_id', 'idx_game_tag_on_tag_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropIndex('idx_games_on_released_at');
        });

        Schema::table('game_category', function (Blueprint $table) {
            $table->dropPrimary('pk_game_category_on_game_id_and_category_id');
            $table->dropIndex('idx_game_category_on_category_id');
        });

        Schema::table('game_tag', function (Blueprint $table) {
            $table->dropPrimary('pk_game_tag_on_game_id_and_tag_id');
            $table->dropIndex('idx_game_tag_on_tag_id');
        });
    }
};
