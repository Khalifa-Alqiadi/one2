<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_players', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('team_id')->index();
            $table->unsignedBigInteger('player_id')->index();

            $table->unsignedBigInteger('season_id')->nullable()->index();

            $table->unsignedBigInteger('position_id')->nullable()->index();
            $table->unsignedBigInteger('detailed_position_id')->nullable()->index();

            $table->unsignedInteger('jersey_number')->nullable();

            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();

            $table->boolean('is_current')->default(true);
            $table->boolean('is_captain')->default(false);

            $table->unsignedBigInteger('transfer_id')->nullable()->index();

            $table->json('payload_json')->nullable();

            $table->timestamps();

            $table->unique(
                ['team_id', 'player_id', 'season_id'],
                'team_player_team_player_season_unique'
            );

//             $table->foreign('team_id')
//                 ->references('id')
//                 ->on('teams')
//                 ->cascadeOnDelete();
//
//             $table->foreign('player_id')
//                 ->references('id')
//                 ->on('players')
//                 ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_player');
    }
};
