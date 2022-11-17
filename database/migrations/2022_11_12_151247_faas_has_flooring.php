<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faas_has_flooring', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('faas_building_profiles_id')->nullable();
            $table->foreignUuid('structural_flooring_id')->nullable();
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
        Schema::dropIfExists('faas_has_flooring');
        //
    }
};
