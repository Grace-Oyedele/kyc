<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_banks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('bank_name');
            $table->string('bank_code');
            $table->string('account_number');
            $table->string('account_name');
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
        Schema::dropIfExists('customer_banks');
    }
}
