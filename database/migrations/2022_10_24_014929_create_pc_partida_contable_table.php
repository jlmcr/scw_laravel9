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
        Schema::create('pc_partida_contable', function (Blueprint $table) {
            //$table->id();

            $table->string('codigo',15)->primary();

            $table->string('descripcion',100)->nullable();
            $table->unsignedInteger('nivel');
            $table->unsignedInteger('correlativo');
            $table->boolean('estado')->nullable()->default(1);

            //! tipo 1:N TIPO
            $table->unsignedBigInteger('tipo_id');

            $table->foreign('tipo_id')
                    ->references('id')
                    ->on('pc_tipo')
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
        Schema::dropIfExists('pc_partida_contable');
    }
};
