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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('numeroFactura',15)->nullable();
            $table->string('codigoAutorizacion',100)->nullable();
            $table->string('ciNitCliente',15);
            $table->string('complemento',5)->nullable();
            $table->string('razonSocialCliente',150);
            $table->float('importeTotal',12,2)->nullable();
            $table->float('ice',12,2)->nullable();
            $table->float('iehd',12,2)->nullable();
            $table->float('ipj',12,2)->nullable();
            $table->float('tasas',12,2)->nullable();
            $table->float('otrosNoSujetosaIva',12,2)->nullable();
            $table->float('exportacionesyExentos',12,2)->nullable();
            $table->float('tasaCero',12,2)->nullable();
            $table->float('descuentos',12,2)->nullable();
            $table->float('gifCard',12,2)->nullable();
            $table->string('estado',2)->nullable();
            $table->string('codigoControl',20)->default('0')->nullable();
            $table->char('tipoVenta',2);

            $table->string('registroContable',20)->nullable();
            $table->unsignedBigInteger('sucursal_id');

            $table->foreign('sucursal_id')
                    ->references('id')
                    ->on('sucursals') //! relacion con (sucursal) 1:N (compras)
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
        Schema::dropIfExists('ventas');
    }
};
