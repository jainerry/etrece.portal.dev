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
        Schema::create('name_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('refID')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('suffix')->nullable();
            $table->date('bdate')->nullable();
            $table->string('contact_no')->nullable();
            $table->integer('sex')->nullable();
            $table->string('municipality_id')->nullable();
            $table->string('address')->nullable();
            $table->char('isActive', 1)->default('Y');
            $table->timestamps();
            $table->unique(['first_name','last_name','bdate']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('name_profiles');
    }
};
