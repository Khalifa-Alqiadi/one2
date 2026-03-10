<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->text('site_title_ar')->nullable();
            $table->text('site_title_en')->nullable();
            $table->text('site_title_ch')->nullable();
            $table->text('site_title_hi')->nullable();
            $table->text('site_title_es')->nullable();
            $table->text('site_title_ru')->nullable();
            $table->text('site_title_pt')->nullable();
            $table->text('site_title_fr')->nullable();
            $table->text('site_title_de')->nullable();
            $table->text('site_title_th')->nullable();
            $table->text('site_title_br')->nullable();

            $table->text('site_desc_ar')->nullable();
            $table->text('site_desc_en')->nullable();
            $table->text('site_desc_ch')->nullable();
            $table->text('site_desc_hi')->nullable();
            $table->text('site_desc_es')->nullable();
            $table->text('site_desc_ru')->nullable();
            $table->text('site_desc_pt')->nullable();
            $table->text('site_desc_fr')->nullable();
            $table->text('site_desc_de')->nullable();
            $table->text('site_desc_th')->nullable();
            $table->text('site_desc_br')->nullable();

            $table->text('site_keywords_ar')->nullable();
            $table->text('site_keywords_en')->nullable();
            $table->text('site_keywords_ch')->nullable();
            $table->text('site_keywords_hi')->nullable();
            $table->text('site_keywords_es')->nullable();
            $table->text('site_keywords_ru')->nullable();
            $table->text('site_keywords_pt')->nullable();
            $table->text('site_keywords_fr')->nullable();
            $table->text('site_keywords_de')->nullable();
            $table->text('site_keywords_th')->nullable();
            $table->text('site_keywords_br')->nullable();

            $table->text('site_webmails')->nullable();
            $table->tinyInteger('notify_messages_status')->nullable();
            $table->tinyInteger('notify_comments_status')->nullable();
            $table->tinyInteger('notify_orders_status')->nullable();
            $table->tinyInteger('notify_table_status')->nullable();
            $table->tinyInteger('notify_private_status')->nullable();
            $table->text('site_url')->nullable();
            $table->tinyInteger('site_status')->default(0);
            $table->text('close_msg')->nullable();

            $table->json('social_links')->nullable();

            $table->text('whatsapp_no')->nullable();

            $table->text('contact_t1_ar')->nullable();
            $table->text('contact_t1_en')->nullable();
            $table->text('contact_t1_ch')->nullable();
            $table->text('contact_t1_hi')->nullable();
            $table->text('contact_t1_es')->nullable();
            $table->text('contact_t1_ru')->nullable();
            $table->text('contact_t1_pt')->nullable();
            $table->text('contact_t1_fr')->nullable();
            $table->text('contact_t1_de')->nullable();
            $table->text('contact_t1_th')->nullable();
            $table->text('contact_t1_br')->nullable();

            $table->text('contact_t3')->nullable();
            $table->text('contact_t4')->nullable();
            $table->text('contact_t5')->nullable();
            $table->text('contact_t6')->nullable();

            $table->text('contact_t7_ar')->nullable();
            $table->text('contact_t7_en')->nullable();
            $table->text('contact_t7_ch')->nullable();
            $table->text('contact_t7_hi')->nullable();
            $table->text('contact_t7_es')->nullable();
            $table->text('contact_t7_ru')->nullable();
            $table->text('contact_t7_pt')->nullable();
            $table->text('contact_t7_fr')->nullable();
            $table->text('contact_t7_de')->nullable();
            $table->text('contact_t7_th')->nullable();
            $table->text('contact_t7_br')->nullable();

            $table->text('style_logo_ar')->nullable();
            $table->text('style_logo_en')->nullable();
            $table->text('style_logo_ch')->nullable();
            $table->text('style_logo_hi')->nullable();
            $table->text('style_logo_es')->nullable();
            $table->text('style_logo_ru')->nullable();
            $table->text('style_logo_pt')->nullable();
            $table->text('style_logo_fr')->nullable();
            $table->text('style_logo_de')->nullable();
            $table->text('style_logo_th')->nullable();
            $table->text('style_logo_br')->nullable();

            $table->text('style_fav')->nullable();;
            $table->text('style_apple')->nullable();
            $table->text('style_color1')->nullable();
            $table->text('style_color2')->nullable();
            $table->text('style_color3')->nullable();
            $table->text('style_color4')->nullable();
            $table->tinyInteger('style_type')->nullable();
            $table->tinyInteger('style_change')->nullable();
            $table->tinyInteger('style_bg_type')->nullable();
            $table->text('style_bg_pattern')->nullable();
            $table->text('style_bg_color')->nullable();
            $table->text('style_bg_image')->nullable();
            $table->tinyInteger('style_subscribe')->nullable();
            $table->tinyInteger('style_footer')->nullable();
            $table->tinyInteger('style_header')->nullable();
            $table->text('style_footer_bg')->nullable();
            $table->tinyInteger('style_preload')->nullable();
            $table->longText('css')->nullable();
            $table->longText('js')->nullable();
            $table->longText('body')->nullable();

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
        Schema::dropIfExists('settings');
    }
}
