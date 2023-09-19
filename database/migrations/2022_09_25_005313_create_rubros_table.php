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
        Schema::create('rubros', function (Blueprint $table) {
            $table->id();
            $table->string('rubro',100);
            $table->integer('aniosVidaUtil');
            $table->string('codCntaActivo',20)->nullable();
            $table->string('codCntaDepreciacion',20)->nullable();
            $table->string('codCntaDepreciacionAcum',20)->nullable();
            $table->boolean('sujetoAdepreciacion');
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
        Schema::dropIfExists('rubros');
    }
};
