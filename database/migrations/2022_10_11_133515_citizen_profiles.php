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
            $table->integer('sex');
            $table->date('bdate');
            $table->string('civilStatus');
            $table->string('brgyID');
            $table->string('purokID');
            $table->string('address');
            $table->string('placeOfOrigin');
            $table->char('isActive', 1)->default('y');
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
