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
        Schema::create('depreciaciones', function (Blueprint $table) {
            $table->id();
            $table->date('fechaInicial')->nullable();
            $table->date('fechaFinal')->nullable();
            $table->boolean('reexpresar')->nullable()->default(0);
            $table->float('meses',5,2)->nullable()->default(0);

            $table->float('valorInicial_depr',9,2)->nullable();//solo hasta 9.999.999
            $table->float('depAcumInicial_depr',9,2)->nullable();
            $table->float('valorFinal_depr',9,2)->nullable();
            $table->float('depAcumFinal_depr',9,2)->nullable();

            $table->string('activoFijo_id',11);
            $table->foreign('activoFijo_id')
                    ->references('id')
                    ->on('activos_fijos') //! relacion con activoFijo 1:N depreciaciones
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                    //->nullOnDelete();

            $table->unsignedBigInteger('ejercicio_id');
            $table->foreign('ejercicio_id')
                    ->references('id')
                    ->on('ejercicios') //! relacion con ejercicio 1:N depreciaciones
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                    //->nullOnDelete();

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
        Schema::dropIfExists('depreciaciones');
    }
};
