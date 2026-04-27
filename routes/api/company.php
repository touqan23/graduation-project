<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;

Route::prefix('company')->group(function () {

    // --- راوتات عامة (لا تحتاج تسجيل دخول) ---
    Route::post('/upload-media', [CompanyController::class, 'upload']);
    Route::get('/initial-page', [CompanyController::class, 'getFirstPage']);
    Route::get('/forms_info', [CompanyController::class, 'getFormDependencies']);
    Route::post('/company_request', [CompanyController::class, 'storeCompanyRequest']);

    // هذا الراوت يفضل أن يكون محمي بـ Admin Middleware لاحقاً، لكن حالياً سأتركه عاماً
    Route::get('/company_ready', [CompanyController::class, 'promoteReadyRequests']);

    // --- راوتات محمية (يجب وجود Token لشركة مسجلة) ---
    Route::middleware('auth:sanctum')->group(function () {
        //products
        Route::post('/store_product', [CompanyController::class, 'storeProduct']);
        Route::post('/update_product/{id}', [CompanyController::class, 'updateProduct']);
        Route::get('/products', [CompanyController::class, 'getProduct']);
        Route::delete('/delete_product/{id}', [CompanyController::class, 'destroyProduct']);
        //
        Route::post('/update_profile', [CompanyController::class, 'updateProfile']);
        Route::get('/company_home', [CompanyController::class, 'getCompanyDashboard']);
        //promption
        Route::post('/store_promotion', [CompanyController::class, 'storePromotion']);
        Route::post('/update_promotion/{id}', [CompanyController::class, 'updatePromotion']);
        Route::delete('/delete_promotion/{id}', [CompanyController::class, 'destroyPromotion']);
        Route::get('/promotions', [CompanyController::class, 'getPromotions']);
    });
});
