<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; // Asegúrate de importar Schedule

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Añade esta línea para que tu comando se ejecute todos los días a medianoche.
Schedule::command('apps:deactivate-expired')->daily();
