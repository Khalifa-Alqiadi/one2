<?php

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
        Schema::create('fixture_goals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fixture_id')->index();   // match id
            $table->unsignedBigInteger('team_id')->nullable()->index();
            $table->unsignedBigInteger('player_id')->nullable()->index();
            $table->unsignedBigInteger('assist_id')->nullable()->index();

            $table->integer('minute')->nullable();
            $table->integer('extra_minute')->nullable(); // +2, +5...
            $table->string('result')->nullable(); // 1-0, 2-1 .. (إذا متوفر)
            $table->string('detail')->nullable(); // normal, penalty, own goal...
            $table->boolean('is_own_goal')->default(false);
            $table->boolean('is_penalty')->default(false);
            $table->boolean('is_home')->default(false);
            $table->boolean('is_slider')->default(false);

            $table->json('payload')->nullable();
            $table->timestamps();

            $table->unique(['fixture_id', 'team_id', 'player_id', 'minute', 'extra_minute'], 'fx_goal_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixture_goals');
    }
};
