<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('standings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('league_id')->nullable()->index();
            $table->unsignedBigInteger('season_id')->nullable()->index();
            $table->unsignedBigInteger('stage_id')->nullable()->index();
            $table->unsignedBigInteger('round_id')->nullable()->index();
            $table->unsignedBigInteger('team_id')->nullable()->index();

            $table->unsignedBigInteger('sportmonks_standing_id')->nullable()->unique();
            $table->unsignedBigInteger('participant_id')->nullable()->index();

            $table->string('group_name')->nullable()->index();
            $table->string('standing_type')->nullable()->index();

            $table->unsignedInteger('position')->nullable()->index();
            $table->unsignedInteger('points')->default(0);

            $table->unsignedInteger('played')->default(0);
            $table->unsignedInteger('won')->default(0);
            $table->unsignedInteger('draw')->default(0);
            $table->unsignedInteger('lost')->default(0);

            $table->unsignedInteger('goals_for')->default(0);
            $table->unsignedInteger('goals_against')->default(0);
            $table->integer('goal_difference')->default(0);

            $table->unsignedInteger('recent_form_points')->nullable();
            $table->string('form', 50)->nullable();

            $table->json('payload_json')->nullable();

            $table->timestamp('synced_at')->nullable()->index();

            $table->timestamps();

            $table->unique(
                ['season_id', 'stage_id', 'round_id', 'participant_id', 'group_name'],
                'standings_unique_row'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standings');
    }
};
