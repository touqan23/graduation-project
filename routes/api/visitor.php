<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;

Route::controller(VisitorController::class)->group(function () {
    Route::get('welcome-page','welcomePage');
    Route::get('companies','getCompanies');
    Route::get('companies/{id}','companyDetails');
    Route::post('support-email','SupportMessage');
});

Route::controller(EventController::class)->group(function () {
    Route::get('events','eventsByDate');
    Route::get('events/{id}','eventDetails');
});


