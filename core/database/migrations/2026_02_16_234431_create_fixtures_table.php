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

        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('league_id')->index();
            $table->unsignedBigInteger('season_id')->index();
            $table->unsignedBigInteger('round_id')->nullable()->index();

            $table->unsignedBigInteger('home_team_id')->nullable()->index();
            $table->unsignedBigInteger('away_team_id')->nullable()->index();

            $table->dateTime('starting_at')->index();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('state_name')->nullable();
            $table->string('state_code')->nullable()->index();

            // score الحالي (وقت المباراة)
            $table->integer('home_score')->nullable();
            $table->integer('away_score')->nullable();

            // ✅ نهائي (يُملأ فقط لو انتهت)
            $table->boolean('is_finished')->default(false)->index();
            $table->integer('ft_home_score')->nullable();
            $table->integer('ft_away_score')->nullable();

            $table->integer('minute')->nullable();
            $table->json('payload')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
