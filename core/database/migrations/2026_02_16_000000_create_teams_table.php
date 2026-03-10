<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id(); // sportmonks team id
            $table->unsignedBigInteger('country_id')->nullable();
            $table->integer('sport_id')->nullable();
            $table->unsignedBigInteger('venue_id')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();
            $table->string('short_code')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('row_no')->default(0);
            $table->integer('founded')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
}
