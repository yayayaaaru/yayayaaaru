<?php

declare(strict_types=1);

use App\Models\Game;
use App\Models\Tag;
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
        Schema::create('game_tag', function (Blueprint $table) {
            $table->foreignIdFor(Tag::class);
            $table->foreignIdFor(Game::class);
            $table->timestamps();
        });

        Schema::table('game_tag', function (Blueprint $table) {
            $table->foreign('tag_id')->references('id')->on('tags');
            $table->foreign('game_id')->references('id')->on('games');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_tag');
    }
};
