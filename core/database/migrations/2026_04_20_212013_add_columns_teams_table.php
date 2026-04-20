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
        Schema::table('teams', function(Blueprint $table){
            $table->boolean('major_competitions')->default(0)->after('type');
            $table->boolean('major_national_teams')->default(0)->after('major_competitions');
        });
        Schema::table('leagues', function(Blueprint $table){
            $table->boolean('is_home')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
