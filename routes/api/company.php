<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;

Route::controller(CompanyController::class)->group(function () {

    // --- راوتات عامة (Public Routes) ---
    Route::post('/upload-media', 'upload');
    Route::get('/initial-page', 'getFirstPage');
    Route::get('/forms_info', 'getFormDependencies');
    Route::post('/company_request', 'storeCompanyRequest');
    Route::get('/company_ready', 'promoteReadyRequests');

    // --- راوتات محمية (Protected Routes) ---
    Route::middleware('auth:sanctum')->group(function () {

        // المنتجات (Products)
        Route::post('/store_product', 'storeProduct');
        Route::post('/update_product/{id}', 'updateProduct');
        Route::get('/products', 'getProduct');
        Route::delete('/delete_product/{id}', 'destroyProduct');

        // الملف الشخصي والداشبورد
        Route::post('/update_profile', 'updateProfile');
        Route::get('/company_home', 'getCompanyDashboard');
        Route::get('/company_profile', 'getCompanyProfile');

        // العروض (Promotions)
        Route::post('/store_promotion', 'storePromotion');
        Route::post('/update_promotion/{id}', 'updatePromotion');
        Route::delete('/delete_promotion/{id}', 'destroyPromotion');
        Route::get('/promotions', 'getPromotions');
    });
});
