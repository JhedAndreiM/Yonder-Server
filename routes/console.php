<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the low stock check command to run every hour
app()->booted(function () {
    $schedule = app(Schedule::class);
    $schedule->command('stock:check-low')->hourly();
});
