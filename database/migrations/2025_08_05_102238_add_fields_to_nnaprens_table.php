<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nnaprens', function (Blueprint $table) {
           /* $table->string('correo',150)->nullable();
            $table->string('telefono',150)->nullable();
            $table->string('domicilio_completo',500)->nullable();
            $table->string('departamento_code',50)->nullable();
            $table->string('departamento_label',250)->nullable();
            $table->string('municipio_code',50)->nullable();
            $table->string('municipio_label',250)->nullable();
            $table->string('city_code',50)->nullable();
            $table->string('city_label',250)->nullable();
            $table->string('barrio_code',50)->nullable();
            $table->string('barrio_label',250)->nullable();
            $table->string('direccion_exacta',500)->nullable();
            
            $table->string('motivo_discrepancia',2000)->nullable();
 */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nnaprens', function (Blueprint $table) {
            //
        });
    }
};
