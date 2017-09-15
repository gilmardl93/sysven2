<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvedorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provedor', function (Blueprint $table) {
            $table->increments('id');
            $table->string('razon_social');
            $table->string('ruc');
            $table->string('telefono1');
            $table->string('telefono2')->nullable();
            $table->string('email')->nullable();
            $table->string('pagina_web')->nullable();
            $table->string('direccion');
            $table->string('distrito');
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
        Schema::dropIfExists('provedor');
    }
}