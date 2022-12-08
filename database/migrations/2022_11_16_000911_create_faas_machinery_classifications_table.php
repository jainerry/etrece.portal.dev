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
        Schema::create('faas_machinery_classifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('refID')->unique();
            $table->string('code')->unique();
            $table->string('name')->unique();
            $table->text('assessmentLevels')->nullable();
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
        Schema::dropIfExists('faas_machinery_classifications');
    }
};
