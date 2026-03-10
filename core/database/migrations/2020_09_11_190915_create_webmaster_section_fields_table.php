<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebmasterSectionFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webmaster_section_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webmaster_id')->constrained(
                table: 'webmaster_sections', indexName: 'webmaster_section_fields_webmaster_id'
            );
            $table->integer('type')->default(0);

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

            $table->string('default_value')->nullable();

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

            $table->integer('row_no')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('required')->default(0);
            $table->tinyInteger('in_table')->default(0);
            $table->tinyInteger('in_search')->default(0);
            $table->tinyInteger('in_listing')->default(0);
            $table->tinyInteger('in_page')->default(0);
            $table->tinyInteger('in_statics')->default(0);
            $table->string('lang_code')->nullable();
            $table->string('categories')->nullable();
            $table->string('css_class')->nullable();
            $table->string('view_permission_groups')->nullable();
            $table->string('add_permission_groups')->nullable();
            $table->string('edit_permission_groups')->nullable();
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
        Schema::dropIfExists('webmaster_section_fields');
    }
}
