<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained(
                table: 'webmaster_banners', indexName: 'banners_section_id'
            );

            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
            $table->string('title_ch')->nullable();
            $table->string('title_hi')->nullable();
            $table->string('title_es')->nullable();
            $table->string('title_ru')->nullable();
            $table->string('title_pt')->nullable();
            $table->string('title_fr')->nullable();
            $table->string('title_de')->nullable();
            $table->string('title_th')->nullable();
            $table->string('title_br')->nullable();

            $table->text('details_ar')->nullable();
            $table->text('details_en')->nullable();
            $table->text('details_ch')->nullable();
            $table->text('details_hi')->nullable();
            $table->text('details_es')->nullable();
            $table->text('details_ru')->nullable();
            $table->text('details_pt')->nullable();
            $table->text('details_fr')->nullable();
            $table->text('details_de')->nullable();
            $table->text('details_th')->nullable();
            $table->text('details_br')->nullable();

            $table->text('code')->nullable();

            $table->string('file_ar')->nullable();
            $table->string('file_en')->nullable();
            $table->string('file_ch')->nullable();
            $table->string('file_hi')->nullable();
            $table->string('file_es')->nullable();
            $table->string('file_ru')->nullable();
            $table->string('file_pt')->nullable();
            $table->string('file_fr')->nullable();
            $table->string('file_de')->nullable();
            $table->string('file_th')->nullable();
            $table->string('file_br')->nullable();

            $table->tinyInteger('video_type')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('link_url')->nullable();

            $table->string('link_ar')->nullable();
            $table->string('link_en')->nullable();
            $table->string('link_ch')->nullable();
            $table->string('link_hi')->nullable();
            $table->string('link_es')->nullable();
            $table->string('link_ru')->nullable();
            $table->string('link_pt')->nullable();
            $table->string('link_fr')->nullable();
            $table->string('link_de')->nullable();
            $table->string('link_th')->nullable();
            $table->string('link_br')->nullable();

            $table->string('icon')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('visits')->default(0);
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
        Schema::dropIfExists('banners');
    }
}
