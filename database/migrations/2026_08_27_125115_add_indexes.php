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
        if (!$this->indexExists('games', 'idx_games_on_released_at')) {
            Schema::table('games', function (Blueprint $table) {
                $table->index('released_at', 'idx_games_on_released_at');
            });
        }

        $this->deduplicate('game_category', ['game_id', 'category_id']);
        $this->deduplicate('game_tag', ['game_id', 'tag_id']);

        if (!$this->indexExists('game_category', 'PRIMARY')) {
            Schema::table('game_category', function (Blueprint $table) {
                $table->primary(['game_id', 'category_id'], 'pk_game_category_on_game_id_and_category_id');
            });
        }

        if (!$this->indexExists('game_category', 'idx_game_category_on_category_id')) {
            Schema::table('game_category', function (Blueprint $table) {
                $table->index('category_id', 'idx_game_category_on_category_id');
            });
        }

        if (!$this->indexExists('game_tag', 'PRIMARY')) {
            Schema::table('game_tag', function (Blueprint $table) {
                $table->primary(['game_id', 'tag_id'], 'pk_game_tag_on_game_id_and_tag_id');
            });
        }

        if (!$this->indexExists('game_tag', 'idx_game_tag_on_tag_id')) {
            Schema::table('game_tag', function (Blueprint $table) {
                $table->index('tag_id', 'idx_game_tag_on_tag_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->indexExists('games', 'idx_games_on_released_at')) {
            Schema::table('games', function (Blueprint $table) {
                $table->dropIndex('idx_games_on_released_at');
            });
        }

        if (!$this->indexExists('game_category', 'idx_game_category_on_game_id')) {
            Schema::table('game_category', function (Blueprint $table) {
                $table->index('game_id', 'idx_game_category_on_game_id');
            });
        }

        if ($this->indexExists('game_category', 'PRIMARY')) {
            Schema::table('game_category', function (Blueprint $table) {
                $table->dropPrimary('pk_game_category_on_game_id_and_category_id');
            });
        }

        if (!$this->indexExists('game_tag', 'idx_game_tag_on_game_id')) {
            Schema::table('game_tag', function (Blueprint $table) {
                $table->index('game_id', 'idx_game_tag_on_game_id');
            });
        }

        if ($this->indexExists('game_tag', 'PRIMARY')) {
            Schema::table('game_tag', function (Blueprint $table) {
                $table->dropPrimary('pk_game_tag_on_game_id_and_tag_id');
            });
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        return collect(DB::select("SHOW INDEX FROM `{$table}`"))
            ->pluck('Key_name')
            ->contains($indexName);
    }

    private function deduplicate(string $table, array $columns): void
    {
        DB::statement("
            DELETE t1 FROM {$table} t1
            INNER JOIN {$table} t2
                ON " . implode(' AND ', array_map(fn ($c) => "t1.{$c} = t2.{$c}", $columns)) . "
            WHERE t1.created_at > t2.created_at
                OR (t1.created_at = t2.created_at AND t1.updated_at > t2.updated_at)
        ");
    }
};
