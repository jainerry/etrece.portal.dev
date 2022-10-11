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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('empoyeeId');
            $table->string('IDNo')->nullable();
            $table->string('lastName');
            $table->string('firstName');
            $table->string('middleName')->nullable();
            $table->string('nickName')->nullable();
            $table->string('birthDate');
            $table->string('bloodType')->nullable();
            $table->string('tinNo')->nullable();
            $table->string('bpNo')->nullable();
            $table->string('emergencyContactPerson')->nullable();
            $table->string('emergencyContactRelationship')->nullable();
            $table->string('emergencyContactAddress1')->nullable();
            $table->string('emergencyContactAddress2')->nullable();
            $table->string('oldIDNo')->nullable();
            $table->char('isActive', 1)->default('Y');
            $table->string('departmentId');
            $table->string('sectionId');
            $table->string('positionId');
            $table->string('picName')->nullable();
            $table->string('halfPicName')->nullable();
            $table->string('signName')->nullable();
            $table->integer('empPrint')->default(0);
            $table->string('workStatus');
            $table->string('remarks');
            $table->string('encryptCode');
            $table->string('contactNo')->nullable();
            $table->integer('smallPrint')->default(0);
            $table->string('suffix')->nullable();
            $table->string('birthPlace')->nullable();
            $table->string('civilStatus');
            $table->string('citizenShip');
            $table->string('citizenShipAcquisition')->nullable();
            $table->string('country');
            $table->string('sex');
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('pagibigNo')->nullable();
            $table->string('philhealthNo')->nullable();
            $table->string('sssNo')->nullable();
            $table->string('landlineNo')->nullable();
            $table->string('email')->nullable();
            $table->string('residentialAddress')->nullable();
            $table->string('permanentAddress')->nullable();
            $table->string('residentialSitio')->nullable();
            $table->string('permanentSitio')->nullable();

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
        Schema::dropIfExists('employees');
    }
};
