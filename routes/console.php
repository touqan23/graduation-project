<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $files = Storage::disk('local')->files('temp');
    foreach ($files as $file) {
        if (Storage::disk('local')->lastModified($file) < now()->week()->getTimestamp()) {
            Storage::disk('local')->delete($file);
        }
    }
})->weekly();
