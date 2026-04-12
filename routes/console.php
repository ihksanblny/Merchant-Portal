<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\EvaluateExpiryStockJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Jadwal Early Warning System (EWS) berjalan tepat jam 12 Malam setiap hari
Schedule::job(new EvaluateExpiryStockJob)->dailyAt('00:00');
