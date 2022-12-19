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
        Schema::create('bus_mayors_permits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('refID')->nullable();
            $table->string('category_id')->nullable(0);
            $table->date('effective_date')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->string('amount')->nullable();
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
        Schema::dropIfExists('bus_mayors_permits');
    }
};
