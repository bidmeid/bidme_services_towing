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
            $table->string('gps')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longtitude')->nullable();
            $table->string('speed')->nullable();
            $table->string('timestamp')->nullable();
            $table->string('status')->nullable();
            $table->string('msg')->nullable();
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
