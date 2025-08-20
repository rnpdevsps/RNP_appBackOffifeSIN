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
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id(); // ID de la clave
            $table->string('api_key')->unique(); // La API key única
            $table->string('app_name')->nullable(); // Nombre de la aplicación o cliente
            $table->integer('status')->default(1);
            $table->timestamp('expires_at')->nullable(); // Fecha de expiración
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
};
