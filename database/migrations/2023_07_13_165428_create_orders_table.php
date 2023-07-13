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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->integer('table_number');
            $table->unsignedBigInteger('cashier_id');
            $table->string('order_type'); //dine-in or take-out
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_status'); //paid or unpaid
            $table->string('order_status'); //preparing or complete
            $table->timestamp('order_time');

            //cashier_id is foreign key and a primary key of user id
            $table->foreign('cashier_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
