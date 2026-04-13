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
        Schema::table('standings', function(Blueprint $table){
            $table->unsignedBigInteger('rule_id')
                ->nullable()
                ->after('payload_json');
            $table->string('rule_name')
                ->nullable()
                ->after('rule_id');
            $table->unsignedBigInteger('rule_type_id')
                ->nullable()
                ->after('rule_name');
            $table->string('rule_type_code')
                ->nullable()
                ->after('rule_type_id');
            $table->string('rule_type_name')
                ->nullable()
                ->after('rule_type_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
