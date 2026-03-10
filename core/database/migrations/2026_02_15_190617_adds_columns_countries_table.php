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
        //

        Schema::table('countries', function (Blueprint $table) {
            $table->integer('sport_id')
                ->nullable()
                ->unique()
                ->after('id');
            $table->boolean('status')->default(1)->after('tel');
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
