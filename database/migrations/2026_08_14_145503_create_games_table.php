<?php

declare(strict_types=1);

use App\Models\Developer;
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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique('unq_games_on_slug');
            $table->string('title');
            $table->longText('description');
            $table->dateTime('released_at');
            $table->string('rating', 8);
            $table->integer('views_count')->default(0);
            $table->timestamps();
        });

        Schema::table('games', function (Blueprint $table) {
            $table->foreignIdFor(Developer::class)->after('id');
            $table->foreign('developer_id')->references('id')->on('developers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
