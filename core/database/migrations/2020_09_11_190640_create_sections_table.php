<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
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

            $table->string('photo')->nullable();
            $table->string('icon')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('visits')->default(0);
            $table->foreignId('webmaster_id')->constrained(
                table: 'webmaster_sections', indexName: 'sections_webmaster_id'
            );
            $table->integer('father_id')->default(0);
            $table->integer('row_no')->default(0);
            $table->integer('popup_id')->nullable();

            $table->longText('css_code')->nullable();
            $table->longText('js_code')->nullable();
            $table->longText('body_code')->nullable();

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
        Schema::dropIfExists('sections');
    }
}
