<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->integer('row_no')->default(0);
            $table->integer('father_id')->default(0);

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

            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('type')->default(0);
            $table->integer('cat_id')->nullable();
            $table->string('link')->nullable();

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
            $table->tinyInteger('target')->default(0);
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
        Schema::dropIfExists('menus');
    }
}
