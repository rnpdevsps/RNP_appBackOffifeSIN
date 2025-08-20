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
        Schema::create('nnaprens', function (Blueprint $table) {
            $table->id();
            $table->string('IdDeclaPreRegistroNNA', 40)->nullable();
            $table->timestamp('FchDeclaPreRegistroNNA')->nullable();
            $table->string('EstadoDeclaPreRegistroNNA', 1)->nullable();
            $table->timestamp('FechaProcesaDeclaPreRegistroNNA')->nullable();
            $table->string('OrigenDeclaracion', 40)->nullable();
            $table->string('DNI_Solicitante', 13)->nullable();
            $table->string('NombresSolicitante', 75)->nullable();
            $table->string('PriApeSolicitante', 55)->nullable();
            $table->string('SeApeSolicitante', 55)->nullable();
            $table->string('TipoRelaSolicitante', 40)->nullable();
            $table->string('ParentescoSolicitante', 40)->nullable();
            $table->string('DNI_NNA',13)->nullable();
            $table->string('NombresNNA', 75)->nullable();
            $table->string('PriApeNNA', 55)->nullable();
            $table->string('SeApeNNA', 55)->nullable();
            $table->timestamp('FechaNacNNA')->nullable();
            $table->string('DNI_Madre', 15)->nullable();
            $table->string('NombresMadre', 75)->nullable();
            $table->string('PriApeMadre',30)->nullable();
            $table->string('SeApeMadre', 30)->nullable();
            $table->integer('IdCiudadDomicilioMadre')->nullable();
            $table->string('NombreCiudadDomicilioMadre', 120)->nullable();
            $table->integer('IdPaisDomicilioMadre')->nullable();
            $table->string('NombrePaisDomicilioMadre', 100)->nullable();
            $table->integer('IdEstadoDomicilioMadre')->nullable();
            $table->string('NombreEstadoDomicilioMadre', 100)->nullable();
            $table->string('IdColDomicilioMadre', 50)->nullable();
            $table->string('NombreColDomicilioMadre', 50)->nullable();
            $table->string('DireccionReferenciaNNAMadre', 75)->nullable();
            $table->string('TelefonoMadre', 30)->nullable();
            $table->string('EmailMadre', 75)->nullable();
            $table->string('DNI_Padre', 13)->nullable();
            $table->string('NombresPadre', 75)->nullable();
            $table->string('PriApePadre', 30)->nullable();
            $table->string('SeApePadre', 30)->nullable();
            $table->integer('IdCiudadDomicilioPadre')->nullable();
            $table->string('NombreCiudadDomicilioPadre', 120)->nullable();
            $table->integer('IdPaisDomicilioPadre')->nullable();
            $table->string('NombrePaisDomicilioPadre', 100)->nullable();
            $table->integer('IdEstadoDomicilioPadre')->nullable();
            $table->string('NombreEstadoDomicilioPadre', 100)->nullable();
            $table->string('IdColDomicilioPadre', 50)->nullable();
            $table->string('NombreColDomicilioPadre', 50)->nullable();
            $table->string('DireccionReferenciaNNAPadre', 75)->nullable();
            $table->string('TelefonoPadre', 30)->nullable();
            $table->string('EmailPadre', 100)->nullable();
            $table->boolean('NNAViveConPadres')->nullable();
            $table->integer('IdCiudadDomicilioNNA')->nullable();
            $table->string('NombreCiudadDomicilioNNA', 120)->nullable();
            $table->integer('IdPaisDomicilioNNA')->nullable();
            $table->string('NombrePaisDomicilioNNA', 100)->nullable();
            $table->integer('IdEstadoDomicilioNNA')->nullable();
            $table->string('NombreEstadoDomicilioNNA', 100)->nullable();
            $table->string('NombreColDomicilioNNA', 150)->nullable();
            $table->string('DireccionReferenciaNNA', 400)->nullable();
            $table->string('EmailNNA', 100)->nullable();
            $table->string('IdGrupoEtnicoNNA', 2)->nullable();
            $table->string('NombreGrupoEtnicoNNA', 100)->nullable();
            $table->boolean('TieneDiscapacidadNNA')->nullable();
            $table->boolean('MostrarDiscapacidadDNI')->nullable();
            $table->string('NNADiscaFisicaMotora', 2)->nullable();
            $table->string('NNADiscaSensorialVisual', 2)->nullable();
            $table->string('NNADiscaSensorialAuditiva', 2)->nullable();
            $table->string('NNADiscaMentalPsiquica', 2)->nullable();
            $table->string('NNADiscaMentalIintelectual', 2)->nullable();
            $table->string('NNADiscaOtra', 50)->nullable();
            $table->string('DNI_PerAutorizadaEntrega', 13)->nullable();
            $table->string('NombresPerAutorizadaEntrega', 100)->nullable();
            $table->string('PriApePerAutorizadaEntrega', 30)->nullable();
            $table->string('SeApePerAutorizadaEntrega', 30)->nullable();
            $table->string('TipoRelaPerAutorizadaEntrega', 40)->nullable();
            $table->string('ParentescoPerAutorizadaEntrega', 40)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nnaprens');
    }
};
