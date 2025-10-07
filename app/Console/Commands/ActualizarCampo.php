<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VencerContratos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:vencer-contratos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DetalleContrato; // Asegúrate de usar el modelo adecuado
use Carbon\Carbon;

// php artisan campo:actualizar  comando para correr el CRON
// * * * * * cd /path/to/your/laravel/application && php artisan schedule:run >> /dev/null 2>&1
//Esta línea le indica a cron que ejecute el comando php artisan schedule:run cada minuto.

class ActualizarCampo extends Command
{
    protected $signature = 'campo:actualizar';
    protected $description = 'Actualizar un campo fecha para vencer el contrato';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Implementar la lógica de actualización
        $registros = DetalleContrato::where('fecha_final', "<", now())->get();

        foreach ($registros as $registro) {
            if ($registro->fecha_final < now()){
                $registro->status = 0; //0 vencido
            }

            $registro->save();
        }

        \Log::info('Campo actualizado correctamente a las ' . Carbon::now());
    }
}
