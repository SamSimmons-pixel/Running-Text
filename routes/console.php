<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

use App\Http\Controllers\KajianController;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call([KajianController::class, 'autoUpdateExpiredKajian'])->dailyAt('23:00');
