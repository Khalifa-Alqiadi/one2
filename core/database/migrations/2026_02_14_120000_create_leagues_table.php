<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaguesTable extends Migration
{
    public function up()
    {
        Schema::create('leagues', function (Blueprint $table) {
            $table->id(); // نفس sportmonks id
            $table->unsignedBigInteger('sport_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();
            $table->string('short_code')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedBigInteger('current_season_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leagues');
    }
}
