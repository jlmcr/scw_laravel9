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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            // $table->char('especificacion',2)->default('1');
            $table->string('nitProveedor',15);
            $table->string('razonSocialProveedor',150);
            $table->string('codigoAutorizacion',100)->nullable();
            $table->string('numeroFactura',15)->nullable();
            $table->string('dim',20)->nullable();
            $table->date('fecha');
            $table->float('importeTotal',12,2)->nullable();
            $table->float('ice',12,2)->nullable();
            $table->float('iehd',12,2)->nullable();
            $table->float('ipj',12,2)->nullable();
            $table->float('tasas',12,2)->nullable();
            $table->float('otrosNoSujetosaCF',12,2)->nullable();
            $table->float('exentos',12,2)->nullable();
            $table->float('tasaCero',12,2)->nullable();
            $table->float('descuentos',12,2)->nullable();
            $table->float('gifCard',12,2)->nullable();
            $table->char('tipoCompra',2);
            $table->string('codigoControl',20)->default('0')->nullable();

            $table->boolean('combustible')->nullable();
            $table->string('ultimoCodigoAutorizacion',100)->nullable();
            $table->string('registroContable',20)->nullable();

            $table->unsignedBigInteger('sucursal_id');

            $table->foreign('sucursal_id')
                    ->references('id')
                    ->on('sucursals') //! relacion con (sucursal) 1:N (compras)
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                    $table->timestamps();

                                /* no es recomendable usar campos autocalculados para editar
            pero si para adicionar */

/*             $table->string('proveedor_nit_id',15);

            $table->foreign('proveedor_nit_id')
                    ->references('nit')
                    ->on('proveedors') //! relacion con (compras) N:1 (proveedores)
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete(); */

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compras');
    }
};
