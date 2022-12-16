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
        Schema::create('treasury_others', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('refID')->unique();
            $table->string('orNo')->unique()->nullable();
            $table->string('businessId')->nullable();
            $table->text('otherFees')->nullable();
            $table->string('totalOtherFees')->nullable();
            $table->text('details')->nullable();
            $table->string('totalDetailsAmount')->nullable();
            // $table->string('assessedBy')->nullable();
            // $table->string('assessedDate')->nullable();
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
        Schema::dropIfExists('treasury_others');
    }
};
