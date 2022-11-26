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
        Schema::create('business_activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('refID')->nullable();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->char('open',1)->nullable();
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
        //
        Schema::dropIfExists('business_activities');
    }
};
