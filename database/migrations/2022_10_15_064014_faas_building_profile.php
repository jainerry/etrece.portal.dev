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
        Schema::create('building_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('arpNo')->unique();
            $table->string('code')->nullable();
            $table->string('primary_owner')->nullable();
            $table->string('tel_no')->nullable();
            $table->string('owner_tin_no')->nullable();
            $table->string('administrator')->nullable();
            $table->string('admin_address')->nullable();
            $table->string('admin_tel_no')->nullable();
            $table->string('admin_tin_no')->nullable();
            $table->string('no_of_street')->nullable();
            $table->string('barangay_id')->nullable();
            $table->string('municipality_id')->nullable();
            $table->string('province_id')->nullable();
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
        Schema::dropIfExists('building_profiles');
    }
};
