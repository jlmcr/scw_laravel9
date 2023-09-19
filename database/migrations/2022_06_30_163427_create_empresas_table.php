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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nit',20)->unique();
            $table->string('denominacionSocial',150);
            $table->string('sociedadTipo',70);
            $table->text('actividad')->nullable();
            $table->string('representanteLegal',50);
            $table->string('ci',20);
            $table->string('complemento',5)->nullable();
            $table->string('extension',3);
            $table->string('celular',10)->nullable();
            $table->string('correo',50)->nullable();
            $table->string('clasificacion',15)->nullable();
            $table->string('rutaNit')->nullable();
            $table->string('rutaCertInscripcion')->nullable();
            $table->string('rutaMatricula')->nullable();
            $table->string('rutaRoe')->nullable();
            $table->boolean('estado')->nullable()->default(1);
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
        Schema::dropIfExists('empresas');
    }
};
