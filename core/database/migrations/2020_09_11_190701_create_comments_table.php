<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained(
                table: 'topics', indexName: 'comments_topic_id'
            );
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->dateTime('date')->nullable();
            $table->text('comment')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('row_no')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('comments');
    }
}
