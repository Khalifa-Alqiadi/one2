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
        Schema::create('popups', function (Blueprint $table) {
            $table->id();
            $table->integer('row_no')->default(0);
            $table->integer('form_id')->nullable();
            $table->integer('show_in')->nullable();

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

            $table->string('photo')->nullable();
            $table->text('settings')->nullable();
            $table->longText('code')->nullable();
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
        Schema::dropIfExists('popups');
    }
};
