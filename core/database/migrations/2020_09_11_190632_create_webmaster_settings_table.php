<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebmasterSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webmaster_settings', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('seo_status')->default(0);
            $table->tinyInteger('analytics_status')->default(0);
            $table->tinyInteger('banners_status')->default(0);
            $table->tinyInteger('inbox_status')->default(0);
            $table->tinyInteger('calendar_status')->default(0);
            $table->tinyInteger('settings_status')->default(0);
            $table->tinyInteger('menus_status')->default(0);
            $table->tinyInteger('file_manager_status')->default(0);
            $table->tinyInteger('tags_status')->default(0);
            $table->tinyInteger('popups_status')->default(0);
            $table->tinyInteger('newsletter_status')->default(0);
            $table->tinyInteger('members_status')->default(0);
            $table->tinyInteger('orders_status')->default(0);
            $table->tinyInteger('shop_status')->default(0);
            $table->tinyInteger('shop_settings_status')->default(0);
            $table->integer('default_currency_id')->default(0);
            $table->string('languages_by_default')->nullable();
            $table->integer('latest_news_section_id')->nullable();
            $table->integer('header_menu_id')->nullable();
            $table->integer('footer_menu_id')->nullable();
            $table->integer('home_banners_section_id')->nullable();
            $table->integer('home_text_banners_section_id')->nullable();
            $table->integer('side_banners_section_id')->nullable();
            $table->integer('contact_page_id')->nullable();
            $table->integer('newsletter_contacts_group')->nullable();
            $table->tinyInteger('new_comments_status')->default(0);
            $table->tinyInteger('links_status')->default(0);

            $table->tinyInteger('image_optimize')->default(1)->nullable();
            $table->tinyInteger('image_resize')->default(1)->nullable();
            $table->integer('image_resize_width')->default(1300)->nullable();
            $table->integer('image_resize_height')->default(800)->nullable();

            $table->tinyInteger('slug_translation')->default(1)->nullable();

            $table->tinyInteger('instant_index')->default(0)->nullable();
            $table->string('instant_index_file')->nullable();
            $table->tinyInteger('instant_index_on_create')->default(0)->nullable();
            $table->tinyInteger('instant_index_on_update')->default(0)->nullable();
            $table->tinyInteger('instant_index_on_delete')->default(0)->nullable();

            $table->tinyInteger('register_status')->default(0);
            $table->integer('permission_group')->default(0);
            $table->tinyInteger('api_status')->default(0);
            $table->string('api_key')->nullable();
            $table->integer('home_content1_section_id')->nullable();
            $table->integer('home_content2_section_id')->nullable();
            $table->integer('home_content3_section_id')->nullable();
            $table->integer('home_content4_section_id')->nullable();
            $table->integer('home_content5_section_id')->nullable();
            $table->integer('home_content6_section_id')->nullable();
            $table->integer('home_content7_section_id')->nullable();
            $table->integer('home_content8_section_id')->nullable();
            $table->integer('home_contents_per_page')->nullable();

            $table->tinyInteger('homepage_type')->default(0);
            $table->integer('landing_page_id')->nullable();

            $table->string('mail_driver')->nullable();
            $table->string('mail_host')->nullable();
            $table->string('mail_port')->nullable();
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
            $table->string('mail_encryption')->nullable();
            $table->string('mail_no_replay')->nullable();
            $table->string('mail_title')->nullable();
            $table->longText('mail_template')->nullable();
            $table->tinyInteger('nocaptcha_status')->default(0);
            $table->string('nocaptcha_secret')->nullable();
            $table->string('nocaptcha_sitekey')->nullable();
            $table->tinyInteger('google_tags_status')->default(0);
            $table->string('google_tags_id')->nullable();

            $table->tinyInteger('login_facebook_status')->default(0);
            $table->string('login_facebook_client_id')->nullable();
            $table->string('login_facebook_client_secret')->nullable();

            $table->tinyInteger('login_twitter_status')->default(0);
            $table->string('login_twitter_client_id')->nullable();
            $table->string('login_twitter_client_secret')->nullable();

            $table->tinyInteger('login_google_status')->default(0);
            $table->string('login_google_client_id')->nullable();
            $table->string('login_google_client_secret')->nullable();

            $table->tinyInteger('login_linkedin_status')->default(0);
            $table->string('login_linkedin_client_id')->nullable();
            $table->string('login_linkedin_client_secret')->nullable();

            $table->tinyInteger('login_github_status')->default(0);
            $table->string('login_github_client_id')->nullable();
            $table->string('login_github_client_secret')->nullable();

            $table->tinyInteger('login_bitbucket_status')->default(0);
            $table->string('login_bitbucket_client_id')->nullable();
            $table->string('login_bitbucket_client_secret')->nullable();

            $table->tinyInteger('dashboard_link_status')->default(0);
            $table->tinyInteger('header_search_status')->default(0);
            $table->tinyInteger('cookie_policy_status')->default(1);
            $table->tinyInteger('text_editor')->default(0);
            $table->string('tiny_key')->nullable();
            $table->string('timezone')->nullable();
            $table->string('version',20)->nullable();
            $table->tinyInteger('license')->default(0);
            $table->text('purchase_code')->nullable();

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
        Schema::dropIfExists('webmaster_settings');
    }
}
