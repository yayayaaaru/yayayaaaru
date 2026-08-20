<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Game;
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
        Schema::create('game_category', function (Blueprint $table) {
            $table->foreignIdFor(Category::class);
            $table->foreignIdFor(Game::class);
            $table->timestamps();
        });

        Schema::table('game_category', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('game_id')->references('id')->on('games');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_category');
    }
};
