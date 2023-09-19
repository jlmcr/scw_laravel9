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
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->boolean('estado')->nullable()->default(1);
            $table->string('nroComprobante',12);
            $table->unsignedInteger('correlativo');
            $table->date('fecha');
            $table->string('concepto',250)->nullable();
            $table->string('notas',100)->nullable();
            $table->string('observaciones',100)->nullable();

            $table->string('documento',30)->nullable();
            $table->string('numeroDocumento',30)->nullable();

            $table->unsignedBigInteger('tipoComprobante_id')->nullable();
            $table->foreign('tipoComprobante_id')
                    ->references('id')
                    ->on('tipos_de_comprobante') //! relacion con tipos_de_comprobante 1:N comprobante
                    ->cascadeOnUpdate()
                    //->cascadeOnDelete();
                    ->nullOnDelete();

            $table->unsignedBigInteger('ejercicio_id');
            $table->foreign('ejercicio_id')
                    ->references('id')
                    ->on('ejercicios') //! relacion con ejercicio 1:N comprobante
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
        Schema::dropIfExists('comprobantes');
    }
};
