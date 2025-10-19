<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogController;

use App\Http\Controllers\Api\WSController;
use App\Http\Controllers\Api\WSKioskosController;
use App\Http\Controllers\Api\ArbolGenealogicoController;
use App\Http\Controllers\Api\CodexController;
use App\Http\Controllers\Api\SendEmailAppController;
use App\Http\Middleware\ValidateApiKey;
use App\Http\Controllers\Api\DBLinkController;
use App\Http\Controllers\Api\ApiRcmsController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware([ValidateApiKey::class])->group(function () {

    Route::get('obtenerParametros', [ApiRcmsController::class, 'obtenerParametros'])->name('obtenerParametros');
    Route::get('obtenerValorParametro/{id}', [ApiRcmsController::class, 'obtenerValorParametro'])->name('obtenerValorParametro');

    Route::get('obtenerListaRCM', [ApiRcmsController::class, 'obtenerListaRCM'])->name('obtenerListaRCM');
    Route::get('obtenerDeptosRCM', [ApiRcmsController::class, 'obtenerDeptosRCM'])->name('obtenerDeptosRCM');
    Route::get('obtenerMunicipiosRCM/{id}', [ApiRcmsController::class, 'obtenerMunicipiosRCM'])->name('obtenerMunicipiosRCM');
    Route::get('obtenerClasificacionRCM', [ApiRcmsController::class, 'obtenerClasificacionRCM'])->name('obtenerClasificacionRCM');

    Route::get('blogs/{slug}/', [BlogController::class, 'viewBlog'])->name('view.blog');
    
    Route::get('obtenerNoticias', [BlogController::class, 'obtenerNoticias'])->name('obtenerNoticias');
    Route::get('obtenerCategorias', [BlogController::class, 'obtenerCategorias'])->name('obtenerCategorias');

    Route::post('obtenerPaises', [DBLinkController::class, 'obtenerPaises'])->name('obtenerPaises');
    Route::post('obtenerDepartamentos', [DBLinkController::class, 'obtenerDepartamentos'])->name('obtenerDepartamentos');
    Route::post('obtenerMunicipios', [DBLinkController::class, 'obtenerMunicipios'])->name('obtenerMunicipios');
    Route::post('obtenerBarrios', [DBLinkController::class, 'obtenerBarrios'])->name('obtenerBarrios');
    Route::post('obtenerGrupoEtnicos/', [DBLinkController::class, 'obtenerGrupoEtnicos'])->name('obtenerGrupoEtnicos');
    Route::post('obtenerParentesco', [DBLinkController::class, 'obtenerParentesco'])->name('obtenerParentesco');
    Route::post('obtenerCiudadano', [DBLinkController::class, 'obtenerCiudadano'])->name('obtenerCiudadano');
    Route::get('obtenerGenero/{id?}', [DBLinkController::class, 'obtenerGenero'])->name('obtenerGenero');

    Route::post('preregistro', [WSController::class, 'addPreRegistroNNA'])->name('preregistro');
    Route::get('obtenerPreRegistroNNA/{id}', [WSController::class, 'obtenerPreRegistroNNA'])->name('obtenerPreRegistroNNA');

    Route::post('obtenerIdentidadxCodigoBarras', [WSController::class, 'obtenerIdentidadxCodigoBarras'])->name('obtenerIdentidadxCodigoBarras');
    Route::get('obtenerCertificadoNacimiento/{id}', [WSController::class, 'obtenerCertificadoNacimiento'])->name('obtenerCertificadoNacimiento');
    Route::get('obtenerValidarCertificado/{id}', [WSController::class, 'obtenerValidarCertificado'])->name('obtenerValidarCertificado');
    Route::get('obtenerInscripcionNacimiento/{id}', [WSController::class, 'obtenerInscripcionNacimiento'])->name('obtenerInscripcionNacimiento');
    Route::get('obtenerArbolGenealogico/{id}', [WSController::class, 'obtenerArbolGenealogico'])->name('obtenerArbolGenealogico');
    Route::get('obtenerArbolGenNuclear/{id}', [WSController::class, 'obtenerArbolGenNuclear'])->name('obtenerArbolGenNuclear');

    Route::post('obtenerArbolGenealogico', [WSController::class, 'obtenerArbolGenealogicoPost'])->name('obtenerArbolGenealogico');

    Route::post('obtenerComparaFotoInscrito', [WSController::class, 'obtenerComparaFotoInscrito'])->name('obtenerComparaFotoInscrito');
    Route::get('obtenerInfoCompletaInscripcion/{id}', [WSController::class, 'obtenerInfoCompletaInscripcion'])->name('obtenerInfoCompletaInscripcion');

    Route::get('validaciondni/{id}', [WSController::class, 'obtenerValidacionDNI'])->name('validaciondni');
    
    Route::get('obtenerDNIParaTraslado/{id}', [WSController::class, 'obtenerDNIParaTraslado'])->name('obtenerDNIParaTraslado');
    Route::post('obtenerCentrosDeEntrega', [WSController::class, 'obtenerCentrosDeEntrega'])->name('obtenerCentrosDeEntrega');

    Route::post('validarhuella', [WSHuellaController::class, 'store'])->name('validarhuella');
    Route::post('sendmail', [SendEmailAppController::class, 'sendmail'])->name('sendmail');

    Route::get('RevisionArbolxPadre/{id}', [ArbolGenealogicoController::class, 'RevisionArbolxPadre'])->name('RevisionArbolxPadre');
    Route::get('RevisionArbolxInscripcion/{id}', [ArbolGenealogicoController::class, 'RevisionArbolxInscripcion'])->name('RevisionArbolxInscripcion');
    Route::post('ActualizarRevisionArbolGen', [ArbolGenealogicoController::class, 'ActualizarRevisionArbolGen'])->name('ActualizarRevisionArbolGen');
    Route::post('ObtenerExpedientes', [WSController::class, 'ObtenerExpedientes'])->name('ObtenerExpedientes');
    
    Route::post('traslado', [WSController::class, 'addTraslado'])->name('traslado');
    Route::get('obtenerTraslado/{id}', [WSController::class, 'obtenerTraslado'])->name('obtenerTraslado');


    Route::group(['prefix' => 'kiosko'], function () {
        Route::get('listakioskos', [WSKioskosController::class, 'ListaKioskos'])->name('listakioskos');
        Route::get('defunciones/{id}', [WSKioskosController::class, 'DefuncionesKiosko'])->name('defunciones');
        Route::get('matrimonios/{id}', [WSKioskosController::class, 'MatrimoniosKiosko'])->name('matrimonios');
        Route::get('ArbolGenealogico/{id}', [WSKioskosController::class, 'ArbolGenealogicoKiosko'])->name('ArbolGenealogico');
        Route::post('ComparaFotoInscrito', [WSKioskosController::class, 'ComparaFotoInscritoKiosko'])->name('ComparaFotoInscrito');
        Route::post('ComparaHuellaInscrito', [WSKioskosController::class, 'ComparaHuellaInscritoKiosko'])->name('ComparaHuellaInscrito');
        Route::post('CertificadoMatrimonio', [WSKioskosController::class, 'CertificadoMatrimonioKiosko'])->name('CertificadoMatrimonio');
        Route::get('lugaresentregadni/{id}', [WSKioskosController::class, 'LugaresdeEntregaDNIKiosko'])->name('lugaresentregadni');
        Route::get('RecuperarUltimoDNI/{id}', [WSKioskosController::class, 'RecuperarUltimoDNIKiosko'])->name('RecuperarUltimoDNI');
        Route::post('crearReciboTGR', [WSKioskosController::class, 'crearReciboTGRKiosko'])->name('crearReciboTGR');
        Route::post('RecuperarReciboTGR', [WSKioskosController::class, 'RecuperarReciboTGR1Kiosko'])->name('RecuperarReciboTGR');
        Route::post('CertificadoDefuncion', [WSKioskosController::class, 'CertificadoDefuncionKiosko'])->name('CertificadoDefuncion');
        Route::post('ValidarReciboTGR1ConOrigen', [WSKioskosController::class, 'ValidarReciboTGR1ConOrigenKiosko'])->name('ValidarReciboTGR1ConOrigen');
        Route::post('ComprobanteReposicionDNI', [WSKioskosController::class, 'ComprobanteReposicionDNIKiosko'])->name('ComprobanteReposicionDNI');
        Route::post('ReimpresionReposicionDNI', [WSKioskosController::class, 'ReimpresionReposicionDNIKiosko'])->name('ReimpresionReposicionDNI');

    });

});