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
        Schema::create('tbl_rute_pricelist', function (Blueprint $table) {
            $table->id();
            $table->integer('golonganKendaraanId');
            $table->integer('typeKendaraanId');
            $table->string('asalPostcode');
            $table->string('tujuanPostcode');
            $table->string('standarHarga');
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
        Schema::dropIfExists('tbl_rute_pricelist');
    }
};
