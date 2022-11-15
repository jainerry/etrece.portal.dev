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
        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // transaction RefID : TRANSID-11152021-0000
            $table->string('refId')->nullable();
            // refID of subject
            $table->string('transId')->nullable();
            //category: module name
            $table->string('category')->nullable();
            // action
            $table->string('type')->nullable();
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
        Schema::dropIfExists('faas_structural_additional_items');
    }
};
