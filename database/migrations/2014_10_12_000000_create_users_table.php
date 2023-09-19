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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            /* $table->string('primer_apellido',20)->nullable();
            $table->string('segundo_apellido',20)->nullable(); */

            $table->string('email')->unique();
            $table->string('password');
            $table->integer('idEmpresaActiva')->nullable();
            $table->integer('idEjercicioActivo')->nullable();
            $table->boolean('mostrarBajas')->nullable()->default(0);            
            $table->boolean('resaltar_inputs_rcv')->nullable()->default(0);
            $table->boolean('colapsar_aside')->nullable()->default(0);
            $table->boolean('hora_fecha_en_reportes_pdf')->nullable()->default(1);


            $table->unsignedBigInteger('tema_id')->nullable()->default(1);
            $table->foreign('tema_id')
                    ->references('id')
                    ->on('temas') //! relacion con temas 1:N users
                    ->cascadeOnUpdate()
                    ->nullOnDelete();

            $table->string('acceso',10)->nullable()->default('Denegado');
            $table->string('rol',20)->nullable()->default('Denegado'); //denegado permitido
            // $rol  == "Administrador" || $rol  == "Contador" || $rol  == "Auxiliar Contable" - "Invitado
            $table->boolean('crear')->nullable()->default(0);
            $table->boolean('editar')->nullable()->default(0);
            $table->boolean('eliminar')->nullable()->default(0);

            $table->boolean('estado')->nullable()->default(1);

            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('current_team_id')->nullable(); //id_equipo_actual - usuarios por equipo
            $table->rememberToken();

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
        Schema::dropIfExists('users');
    }
};
