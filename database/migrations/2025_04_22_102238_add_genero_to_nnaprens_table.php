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
            $table->string('Genero_NNA',1)->nullable()->after('DNI_NNA');
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
