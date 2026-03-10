<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalyticsPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analytics_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained(
                table: 'analytics_visitors', indexName: 'analytics_pages_visitor_id'
            );
            $table->string('ip')->nullable();
            $table->text('title')->nullable();
            $table->string('name')->nullable();
            $table->text('query')->nullable();
            $table->string('load_time')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->index(['date','time']);
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
        Schema::dropIfExists('analytics_pages');
    }
}
