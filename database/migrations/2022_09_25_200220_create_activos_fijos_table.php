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
        Schema::create('activos_fijos', function (Blueprint $table) {
            $table->string('id',11)->primary();
            $table->unsignedInteger('correlativo')->nullable(); //0 a 429.4967.295 // int -2147483648 a 2147483647
            $table->string('activoFijo',100);
            $table->integer('cantidad');
            $table->string('medida',30)->nullable();
            $table->float('valorInicial',9,2)->nullable();
            $table->float('depAcumInicial',9,2)->nullable();
            $table->string('situacion',10); //nuevo, saldo
            $table->string('estadoAF',6);//alta baja
            $table->date('fechaCompraRegistro')->nullable();
            $table->string('documento',30)->nullable();
            $table->string('numeroDocumento',30)->nullable();

            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->foreign('empresa_id')
                    ->references('id')
                    ->on('empresas') //! relacion con empresa 1:N
                    ->cascadeOnUpdate()
                    ->nullOnDelete();


            $table->unsignedBigInteger('rubro_id')->nullable();
            $table->foreign('rubro_id')
                    ->references('id')
                    ->on('rubros') //! relacion con rubros 1:N
                    ->cascadeOnUpdate()
                    ->nullOnDelete();


            // otra manera de hacerlo
/*             $table->foreignId('empresa_id')
            ->nullable()
            ->constrained('empresas')
            ->cascadeOnUpdate()
            ->nullOnDelete();
 */

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
        Schema::dropIfExists('activos_fijos');
    }
};
