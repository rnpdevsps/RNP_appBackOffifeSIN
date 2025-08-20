<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up()
    {
        // Cambiar el valor por defecto de la columna guard_name
        DB::statement("ALTER TABLE permissions MODIFY guard_name VARCHAR(50) DEFAULT 'web'");
    }

    public function down()
    {
        // Revertir el cambio si es necesario
        DB::statement("ALTER TABLE permissions MODIFY guard_name VARCHAR(50) DEFAULT 'web'");
    }


};
