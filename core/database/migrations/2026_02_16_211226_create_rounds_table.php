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

        Schema::create('rounds', function (Blueprint $table) {
            $table->id(); // sportmonks round id
            $table->unsignedBigInteger('league_id')->index();
            $table->unsignedBigInteger('season_id')->nullable()->index();
            $table->unsignedBigInteger('stage_id')->nullable()->index();
            $table->string('name')->nullable();
            $table->boolean('finished')->default(true);
            $table->boolean('is_current')->default(false);
            $table->boolean('games_in_current_week')->default(false);
            $table->dateTime('starting_at')->nullable();
            $table->dateTime('ending_at')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rounds');
    }
};
