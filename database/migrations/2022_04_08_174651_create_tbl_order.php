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
        Schema::create('tbl_order', function (Blueprint $table) {
            $table->id();
			$table->string('ticket');
			$table->integer('customerId');
			$table->integer('ruteId');
			$table->integer('kondisiKendaraanId');
			$table->integer('JenisKendaraanId');
			$table->integer('typeKendaraanId');
			$table->string('orderType', 20);
			$table->string('latLongAsal', 200);
			$table->string('alamatAsal', 200);
			$table->string('latLongTujuan', 200);
			$table->string('alamatTujuan', 200);
			$table->string('telp', 13);
			$table->date('orderCost');
			$table->date('orderDate');
			$table->string('orderTime');
			$table->string('orderStatus', 200);
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
        Schema::dropIfExists('tbl_order');
    }
};
