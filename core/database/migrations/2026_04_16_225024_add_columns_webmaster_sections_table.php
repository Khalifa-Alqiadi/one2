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
        Schema::table('webmaster_sections', function(Blueprint $table){
            $table->integer('sportmonks_status')->default(0)->after('sections_status');
        });
        Schema::table('topics', function(Blueprint $table){
            $table->unsignedBigInteger('league_id')->nullable()->after('section_id');
            $table->unsignedBigInteger('team_id')->nullable()->after('league_id');
            $table->unsignedBigInteger('fixture_id')->nullable()->after('team_id');
        });
        // Schema::table('teams', function(Blueprint $table){
        //     $table->boolean('placeholder')->default(false)->after('founded');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
