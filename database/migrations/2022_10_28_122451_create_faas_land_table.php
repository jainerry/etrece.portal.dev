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
        Schema::create('faas_lands', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('refID')->unique();
            $table->string('ARPNo')->unique()->nullable();
            $table->string('pin')->nullable();
            $table->string('transactionCode')->nullable();
            $table->string('octTctNo')->nullable();
            $table->string('survey_no',25)->nullable();
            $table->string('lotNo')->nullable();
            $table->string('blkNo')->nullable();
            $table->string('previousOwnerId')->nullable();
            $table->string('primaryOwnerId')->nullable();
            $table->string('ownerAddress')->nullable();
            $table->string('ownerTelephoneNo')->nullable();
            $table->string('ownerTinNo')->nullable();
            $table->string('administrator')->nullable();
            $table->string('administratorAddress')->nullable();
            $table->string('administratorTelephoneNo')->nullable();
            $table->string('administratorTinNo')->nullable();
            $table->string('noOfStreet')->nullable();
            $table->string('barangayId')->nullable();
            $table->string('cityId')->nullable();
            $table->string('provinceId')->nullable();
            $table->string('propertyBoundaryNorth')->nullable();
            $table->string('propertyBoundaryEast')->nullable();
            $table->string('propertyBoundarySouth')->nullable();
            $table->string('propertyBoundaryWest')->nullable();
            $table->string('landSketch')->nullable();
            $table->string('totalArea')->default(0);
            $table->boolean('isActive')->default(1);
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
        Schema::dropIfExists('faas_lands');
    }
};
