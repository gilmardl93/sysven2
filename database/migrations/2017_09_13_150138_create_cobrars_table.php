<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCobrarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cobrar', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero');
            $table->decimal('a_cuenta');
            $table->decimal('debe');
            $table->decimal('monto_total');
            $table->dateTime("fecha_hora");
            $table->integer('idcliente');
            $table->integer('idusuario');
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
        Schema::dropIfExists('cobrar');
    }
}
