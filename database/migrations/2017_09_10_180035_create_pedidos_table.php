<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcliente')->nullable();
            $table->integer('idproducto');
            $table->string('serie')->default('001');
            $table->string('numero')->default('000000');
            $table->integer('cantidad')->default(1);
            $table->text('descripcion')->nullable();
            $table->decimal('a_cuenta',10,2)->nullable();
            $table->decimal('debe',10,2)->nullable();
            $table->decimal('monto_total',10,2)->nullable();
            $table->dateTime('fecha_hora');
            $table->integer('idusuario');
            $table->softDeletes();
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
        Schema::dropIfExists('pedidos');
    }
}
