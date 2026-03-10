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
        Schema::create('topic_blocks', function (Blueprint $table) {
            $table->id();
            $table->integer('row_no')->default(0);
            $table->foreignId('topic_id')->constrained(
                table: 'topics', indexName: 'page_blocks_topic_id'
            );

            $table->string('block_name')->nullable();

            $table->tinyInteger('type')->default(0);
            $table->longText('content')->nullable();

            $table->tinyInteger('title_status')->default(0);
            $table->tinyInteger('desc_status')->default(0);
            $table->tinyInteger('image_status')->default(0);
            $table->tinyInteger('divider_status')->default(0);
            $table->tinyInteger('more_btn_status')->default(0);
            $table->string('bg_color',30)->nullable();
            $table->string('css_classes')->nullable();

            $table->tinyInteger('status')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_blocks');
    }
};
