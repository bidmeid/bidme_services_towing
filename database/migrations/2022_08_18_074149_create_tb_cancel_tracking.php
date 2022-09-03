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
        Schema::create('tbl_tracking', function (Blueprint $table) {
            $table->id();
            $table->integer('orderId');
            $table->string('gps');
            $table->string('latitude');
            $table->string('longtitude');
            $table->string('speed');
            $table->string('timestamp');
            $table->string('status');
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
        Schema::dropIfExists('tb_cancel_order');
    }
};
