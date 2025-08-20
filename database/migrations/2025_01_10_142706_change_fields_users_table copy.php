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
       /* DB::statement("ALTER TABLE users  
ADD (
    'ADDRESS' VARCHAR2(191),
    'COUNTRY_CODE' VARCHAR2(191) DEFAULT '504' NOT NULL ENABLE,
    'COUNTRY' VARCHAR2(191) DEFAULT 'Honduras',
    'PHONE_VERIFIED_AT' TIMESTAMP (6),
    'PHONE' VARCHAR2(191),
    'DARK_LAYOUT' NUMBER(1,0) DEFAULT 0,
    'RTL_LAYOUT' NUMBER(1,0) DEFAULT 0,
    'TRANSPRENT_LAYOUT' NUMBER(1,0) DEFAULT 1,
    'THEME_COLOR' VARCHAR2(191) DEFAULT 'theme-2',
    'USERS_GRID_VIEW' NUMBER(1,0) DEFAULT 0,
    'FORMS_GRID_VIEW' NUMBER(1,0) DEFAULT 0
);");*/
    }

    public function down()
    {
        // Revertir el cambio si es necesario
    }


};
