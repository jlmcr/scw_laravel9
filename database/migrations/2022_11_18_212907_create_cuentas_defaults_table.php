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
        Schema::create('cuentas_defaults', function (Blueprint $table) {
            $table->id();
            $table->string('contexto',20);
            $table->string('factura_tipo',20)->default('no aplica');
            $table->string('referencia',50);
            $table->string('tipo',20);

            //! subcuenta 1 : N detalle comprobante (hereda la clave)
            $table->string('subcuenta_id',15)->nullable();

            $table->foreign('subcuenta_id')
                    ->references('id')
                    ->on('pc_sub_cuenta')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();

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
        Schema::dropIfExists('cuentas_defaults');
    }
};
