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
        Schema::create('sucursals', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion',50);
            $table->string('direccion',150)->nullable();
            $table->char('estado',2)->nullable()->default('1');

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
        Schema::dropIfExists('sucursals');
    }
};
