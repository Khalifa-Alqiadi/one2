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
        Schema::table('fixtures', function (Blueprint $table) {
            $table->json('events_json')->nullable()->after('away_score');
            $table->json('statistics_json')->nullable()->after('events_json');
            $table->json('lineups_json')->nullable()->after('statistics_json');
            $table->json('win_probabilities_json')->nullable()->after('lineups_json');
            $table->timestamp('details_synced_at')->nullable()->after('win_probabilities_json');
        });
    }

    public function down(): void
    {
        Schema::table('fixtures', function (Blueprint $table) {
            $table->dropColumn([
                'events_json',
                'statistics_json',
                'lineups_json',
                'win_probabilities_json',
                'details_synced_at',
            ]);
        });
    }
};
