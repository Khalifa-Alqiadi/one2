<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trophies', function (Blueprint $table) {
            $table->id();

            // polymorphic
            $table->morphs('awardable');

            // بيانات الجائزة من SportMonks
            $table->unsignedBigInteger('sportmonks_trophy_id')->nullable()->unique();
            $table->unsignedBigInteger('sportmonks_relation_id')->nullable()->index();

            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();

            $table->unsignedBigInteger('country_id')->nullable()->index();
            $table->unsignedBigInteger('sport_id')->nullable()->index();

            $table->integer('season')->nullable();
            $table->date('date')->nullable();
            $table->text('description')->nullable();

            $table->json('payload_json')->nullable();

            $table->timestamps();

            $table->index(['awardable_type', 'awardable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trophies');
    }
};
