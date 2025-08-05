<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AppClient;
use Illuminate\Support\Carbon;

class DeactivateExpiredApps extends Command
{
    /**
     * El nombre y la firma del comando de consola.
     * Así es como lo llamaremos: php artisan apps:deactivate-expired
     */
    protected $signature = 'apps:deactivate-expired';

    /**
     * La descripción del comando de consola.
     */
    protected $description = 'Desactiva las aplicaciones cuyo token ha expirado';

    /**
     * Ejecuta la lógica del comando.
     */
    public function handle()
    {
        $this->info('Buscando aplicaciones expiradas para desactivar...');

        // Buscamos apps que estén activas, tengan fecha de caducidad y esa fecha ya haya pasado.
        $expiredApps = AppClient::where('is_active', true)
                                  ->whereNotNull('expires_at')
                                  ->where('expires_at', '<', Carbon::now())
                                  ->get();

        if ($expiredApps->isEmpty()) {
            $this->info('No se encontraron aplicaciones expiradas.');
            return 0;
        }

        foreach ($expiredApps as $app) {
            $app->is_active = false;
            $app->save();
            $this->line("La aplicación '{$app->name}' ha sido desactivada.");
        }

        $this->info("Proceso completado. Se desactivaron {$expiredApps->count()} aplicaciones.");
        return 0;
    }
}
