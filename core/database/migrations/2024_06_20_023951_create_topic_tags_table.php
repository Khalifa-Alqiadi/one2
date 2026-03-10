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
        Schema::create('topic_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained(
                table: 'topics', indexName: 'topic_tags_topic_id'
            );
            $table->foreignId('tag_id')->constrained(
                table: 'tags', indexName: 'topic_tags_tag_id'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_tags');
    }
};
