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
        Schema::create('tbl_invoice', function (Blueprint $table) {
            $table->id();
            $table->integer('orderId');
            $table->integer('biddingId');
            $table->integer('driverId');
            $table->string('noInvoice');
            $table->string('noTnkbTowing');
            $table->string('paymentMethod');
            $table->string('billing');
            $table->string('paymentStatus');
            $table->string('bankName');
            $table->string('accName');
            $table->string('accNo');
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
    }
};
