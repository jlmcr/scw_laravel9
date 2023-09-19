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
        Schema::create('tipos_de_cambio', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->float('ufv',8,6);
            //$table->float('dolar',7,5);
            $table->timestamps();
            //no irá con clave foranea
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipos_de_cambio');
    }
};
