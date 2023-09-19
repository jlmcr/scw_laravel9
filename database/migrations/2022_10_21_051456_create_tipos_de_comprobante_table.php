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
        Schema::create('tipos_de_comprobante', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',16);
            $table->string('color',50)->nullable()->nullable()->default('bg-white');
            $table->boolean('estado')->nullable()->default(1);
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipos_de_comprobante');
    }
};
