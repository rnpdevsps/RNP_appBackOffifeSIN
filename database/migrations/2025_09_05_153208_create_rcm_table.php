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
        Schema::create('rcms', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 14)->nullable();
            $table->string('name', 500)->nullable();
            $table->integer('idDepto')->nullable();
            $table->integer('idMunicipio')->nullable();
            $table->integer('status')->comment('1 -> activo / 0 -> inactivo')->default('1');
            $table->foreignId('id_clasificacion')->nullable()->constrained('clasificacion');

            $table->string('direccion', 500)->nullable();
            $table->string('foto', 500)->nullable();
            $table->string('latitud', 500)->nullable();
            $table->string('longitud', 500)->nullable();
            $table->string('telefono', 500)->nullable();
            $table->timestamp('date_end_inactive')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rcms');
    }
};
