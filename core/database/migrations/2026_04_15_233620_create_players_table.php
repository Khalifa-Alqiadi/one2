<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); // SportMonks ID

            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();

            $table->string('common_name')->nullable();

            $table->string('image_path')->nullable();

            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();

            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable();

            $table->unsignedBigInteger('country_id')->nullable()->index();
            $table->unsignedBigInteger('nationality_id')->nullable()->index();

            $table->unsignedBigInteger('position_id')->nullable()->index();
            $table->unsignedBigInteger('detailed_position_id')->nullable()->index();

            $table->string('foot', 20)->nullable();
            $table->unsignedBigInteger('sport_id')->nullable()->index();

            $table->json('payload_json')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
