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
        Schema::create('traslados', function (Blueprint $table) {
            $table->id(); // ID autoincremental

            $table->string('dni', 14)->nullable(); // DNI del ciudadano
            $table->string('num_secuencia', 50)->nullable(); // Número de secuencia

            // Origen
            $table->string('codigo_departamento_origen', 50)->nullable();
            $table->string('nombre_departamento_origen', 500)->nullable();
            $table->string('codigo_municipio_origen', 50)->nullable();
            $table->string('nombre_municipio_origen', 500)->nullable();
            $table->string('codigo_centro_entrega_origen', 50)->nullable();
            $table->string('nombre_centro_entrega_origen', 500)->nullable();

            // Destino
            $table->string('codigo_departamento_destino', 50)->nullable();
            $table->string('nombre_departamento_destino', 500)->nullable();
            $table->string('codigo_municipio_destino', 50)->nullable();
            $table->string('nombre_municipio_destino', 500)->nullable();
            $table->string('codigo_centro_entrega_destino', 50)->nullable();
            $table->string('nombre_centro_entrega_destino', 500)->nullable();

            // Contacto
            
            $table->string('correo', 150)->nullable();
            $table->string('telefono', 20)->nullable();

            // Fecha de inicio de la gestión
            $table->timestamp('fecha_inicio_gestion')->nullable();

            // Estatus de la gestión
            $table->integer('estatus')->comment('1 -> pendiente / 0 -> procesado')->default('1');

            $table->timestamps(); // created_at y updated_at
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('traslados');
    }
};