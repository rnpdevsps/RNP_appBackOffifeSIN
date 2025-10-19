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
        /* Schema::table('api_logs', function (Blueprint $table) {
            // En Oracle, no uses NOT NULL sin default
            $table->char('uuid', 36)->nullable();

            // Usa integer o decimal según lo que necesites
            $table->integer('http_status')->nullable();

            // Usa decimal en lugar de float para compatibilidad
            $table->decimal('execution_time_ms', 10, 3)->nullable();
        }); */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /* Schema::table('api_logs', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'http_status', 'execution_time_ms']);
        }); */
    }
};
