<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
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

            $table->longText('details_ar')->nullable();
            $table->longText('details_en')->nullable();
            $table->longText('details_ch')->nullable();
            $table->longText('details_hi')->nullable();
            $table->longText('details_es')->nullable();
            $table->longText('details_ru')->nullable();
            $table->longText('details_pt')->nullable();
            $table->longText('details_fr')->nullable();
            $table->longText('details_de')->nullable();
            $table->longText('details_th')->nullable();
            $table->longText('details_br')->nullable();

            $table->date('date')->nullable();
            $table->date('expire_date')->nullable();
            $table->tinyInteger('video_type')->nullable();
            $table->string('photo_file')->nullable();
            $table->string('attach_file')->nullable();
            $table->text('video_file')->nullable();
            $table->text('audio_file')->nullable();
            $table->string('icon')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('featured')->default(0);
            $table->integer('visits')->default(0);
            $table->foreignId('webmaster_id')->constrained(
                table: 'webmaster_sections', indexName: 'topics_webmaster_id'
            );
            $table->integer('section_id')->nullable();
            $table->integer('row_no')->default(0);

            $table->integer('form_id')->nullable();
            $table->integer('popup_id')->nullable();
            $table->integer('topic_id')->nullable();

            $table->longText('css_code')->nullable();
            $table->longText('js_code')->nullable();
            $table->longText('body_code')->nullable();

            $table->boolean('hide_header')->default(0);
            $table->boolean('hide_footer')->default(0);

            $table->string('seo_title_ar')->nullable();
            $table->string('seo_title_en')->nullable();
            $table->string('seo_title_ch')->nullable();
            $table->string('seo_title_hi')->nullable();
            $table->string('seo_title_es')->nullable();
            $table->string('seo_title_ru')->nullable();
            $table->string('seo_title_pt')->nullable();
            $table->string('seo_title_fr')->nullable();
            $table->string('seo_title_de')->nullable();
            $table->string('seo_title_th')->nullable();
            $table->string('seo_title_br')->nullable();

            $table->text('seo_description_ar')->nullable();
            $table->text('seo_description_en')->nullable();
            $table->text('seo_description_ch')->nullable();
            $table->text('seo_description_hi')->nullable();
            $table->text('seo_description_es')->nullable();
            $table->text('seo_description_ru')->nullable();
            $table->text('seo_description_pt')->nullable();
            $table->text('seo_description_fr')->nullable();
            $table->text('seo_description_de')->nullable();
            $table->text('seo_description_th')->nullable();
            $table->text('seo_description_br')->nullable();

            $table->text('seo_keywords_ar')->nullable();
            $table->text('seo_keywords_en')->nullable();
            $table->text('seo_keywords_ch')->nullable();
            $table->text('seo_keywords_hi')->nullable();
            $table->text('seo_keywords_es')->nullable();
            $table->text('seo_keywords_ru')->nullable();
            $table->text('seo_keywords_pt')->nullable();
            $table->text('seo_keywords_fr')->nullable();
            $table->text('seo_keywords_de')->nullable();
            $table->text('seo_keywords_th')->nullable();
            $table->text('seo_keywords_br')->nullable();

            $table->string('seo_url_slug_ar')->nullable();
            $table->string('seo_url_slug_en')->nullable();
            $table->string('seo_url_slug_ch')->nullable();
            $table->string('seo_url_slug_hi')->nullable();
            $table->string('seo_url_slug_es')->nullable();
            $table->string('seo_url_slug_ru')->nullable();
            $table->string('seo_url_slug_pt')->nullable();
            $table->string('seo_url_slug_fr')->nullable();
            $table->string('seo_url_slug_de')->nullable();
            $table->string('seo_url_slug_th')->nullable();
            $table->string('seo_url_slug_br')->nullable();

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
        Schema::dropIfExists('topics');
    }
}
