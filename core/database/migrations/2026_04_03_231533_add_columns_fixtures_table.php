<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fixtures', function (Blueprint $table) {
            $table->json('tv_stations_json')->nullable()->after('win_probabilities_json');
            $table->json('injuries_json')->nullable()->after('tv_stations_json');
            $table->json('suspensions_json')->nullable()->after('injuries_json');
        });
    }

    public function down(): void
    {
        Schema::table('fixtures', function (Blueprint $table) {
            $table->dropColumn([
                'tv_stations_json',
                'injuries_json',
                'suspensions_json',
            ]);
        });
    }
};
