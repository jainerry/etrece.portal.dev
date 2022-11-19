<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('citizen_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('refID')->unique();
            $table->string('fName')->nullable();
            $table->string('mName')->nullable();
            $table->string('lName')->nullable();
            $table->string('suffix')->nullable();
            $table->integer('sex')->nullable();
            $table->date('bdate')->nullable();
            $table->foreignUuid('civilStatus')->nullable();
            $table->foreignUuid('brgyID')->nullable();
            $table->foreignUuid('purokID')->nullable();
            $table->string('address')->nullable();
            $table->string('placeOfOrigin')->nullable();
            $table->char('isActive', 1)->default('Y');
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
        Schema::dropIfExists('citizen_profiles');
    }
};
