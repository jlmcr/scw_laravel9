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
        Schema::create('pc_sub_cuenta', function (Blueprint $table) {
            $table->string('id',15)->primary();

            //! esto 0:1 pc_partida_contable
            $table->string('codigo_partida',15);

            $table->foreign('codigo_partida')
                    ->references('codigo')
                    ->on('pc_partida_contable')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

            //! cuenta 1 : N esto (hereda la clave del nivel anterior)
            $table->string('cuenta_id',15);

            $table->foreign('cuenta_id')
                    ->references('id')
                    ->on('pc_cuenta')
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
        Schema::dropIfExists('pc_sub_cuenta');
    }
};
