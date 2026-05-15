<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();

            $table->unsignedBigInteger('league_id')->nullable()->index();
            $table->unsignedBigInteger('season_id')->nullable()->index();
            $table->unsignedBigInteger('stage_id')->nullable()->index();

            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();

            $table->integer('sort_order')->nullable()->index();

            $table->boolean('finished')->default(false)->index();
            $table->boolean('is_current')->default(false)->index();

            $table->dateTime('starting_at')->nullable()->index();
            $table->dateTime('ending_at')->nullable()->index();

            $table->timestamps();

            $table->foreign('league_id')->references('id')->on('leagues')->nullOnDelete();
            $table->foreign('season_id')->references('id')->on('seasons')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
