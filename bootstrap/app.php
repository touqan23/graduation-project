<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php', // أضفنا سطر الأوامر
        health: '/up',
        then: function () {
            // 1. راوتات صديقتك (Auth)
            Route::middleware('api')
                ->prefix('api/auth')
                ->group(base_path('routes/api/auth.php'));

            // 2. راوتاتك الخاصة (Company)
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api/company.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // --- تخصيص رد الـ Throttle (تجاوز المحاولات) ---
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'لقد تجاوزت عدد المحاولات المسموح بها. يرجى المحاولة بعد قليل.',
                    'meta'    => [
                        'retry_after_seconds' => $e->getHeaders()['Retry-After'] ?? null,
                    ]
                ], 429);
            }
        });

        // --- تخصيص رد أخطاء التحقق (Validation Errors) ---
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                $errors = collect($e->errors())->mapWithKeys(function ($messages, $field) {
                    return [$field => collect($messages)->map(function ($message) {
                        if (is_array($message)) {
                            return $message;
                        }
                        return [
                            'message' => $message,
                        ];
                    })];
                })->toArray();

                return response()->json([
                    'status'  => 'error',
                    'message' => 'Validation failed.',
                    'errors'  => $errors,
                ], 422);
            }
        });
    })->create();
