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
        Schema::create('ejercicios', function (Blueprint $table) {
            $table->id();
            $table->date('fechaInicio');
            $table->date('fechaCierre');
            $table->integer('ejercicioFiscal');
            $table->boolean('estado')->nullable()->default(1);//activo

            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')
                    ->references('id')
                    ->on('empresas') //! relacion con empresa 1:N
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

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
        Schema::dropIfExists('ejercicios');
    }
};
