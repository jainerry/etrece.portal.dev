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
            $table->string('employeeId');
            $table->string('IDNo')->nullable();
            $table->string('lastName');
            $table->string('firstName');
            $table->string('middleName')->nullable();
            $table->string('nickName')->nullable();
            $table->string('birthDate');
            $table->string('bloodType')->nullable();
            $table->string('tinNo')->nullable();
            $table->string('gsisNo')->nullable();
            $table->string('emergencyContactPerson')->nullable();
            $table->string('emergencyContactRelationship')->nullable();
            $table->string('emergencyContactNo')->nullable();
            $table->string('emergencyContactAddress1')->nullable();
            $table->string('emergencyContactAddress2')->nullable();
            $table->string('oldIDNo')->nullable();
            $table->char('isActive', 1)->default('Y');
            $table->string('officeId');
            $table->string('sectionId');
            $table->string('positionId');
            $table->string('idPicture')->nullable();
            $table->string('halfPicture')->nullable();
            $table->string('signature')->nullable();
            $table->string('appointmentId');
            $table->string('remarks');
            $table->string('cellphoneNo')->nullable();
            $table->string('suffix')->nullable();
            $table->string('birthPlace')->nullable();
            $table->string('civilStatus');
            $table->string('citizenShip');
            $table->string('citizenShipAcquisition')->nullable();
            $table->string('dualCitizenCountry')->nullable();
            $table->string('sex');
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('pagibigNo')->nullable();
            $table->string('philhealthNo')->nullable();
            $table->string('sssNo')->nullable();
            $table->string('telephoneNo')->nullable();
            $table->string('email')->nullable();
            $table->string('residentialAddress')->nullable();
            $table->string('permanentAddress')->nullable();
            $table->string('residentialStreet')->nullable();
            $table->string('permanentStreet')->nullable();

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
