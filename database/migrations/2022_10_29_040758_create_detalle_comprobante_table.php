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
        Schema::create('detalle_comprobante', function (Blueprint $table) {
            $table->id();
            $table->float('debe',12,2)->nullable();
            $table->float('haber',12,2)->nullable();
            $table->unsignedBigInteger('orden')->nullable()->default(0);

            //! comprobante 1 : N detalle comprobante (hereda la clave)
            $table->unsignedBigInteger('comprobante_id');

            $table->foreign('comprobante_id')
                    ->references('id')
                    ->on('comprobantes')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();


            //! subcuenta 1 : N detalle comprobante (hereda la clave)
            $table->string('subcuenta_id',15);

            $table->foreign('subcuenta_id')
                    ->references('id')
                    ->on('pc_sub_cuenta')
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
        Schema::dropIfExists('detalle_comprobante');
    }
};
