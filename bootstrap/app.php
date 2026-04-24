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
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api/auth') // سيصبح المسار مثلاً: /api/auth/login
                ->group(base_path('routes/api/auth.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // تخصيص رد الـ Throttle
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
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {

                // تحويل الأخطاء مع الحفاظ على التنسيق الذي ترسلينه من الـ Action
                $errors = collect($e->errors())->mapWithKeys(function ($messages, $field) {
                    return [$field => collect($messages)->map(function ($message) {
                        // إذا كان الخطأ مصفوفة (جاي من الأكشن) نرجعه كما هو
                        if (is_array($message)) {
                            return $message;
                        }
                        // إذا كان نص (جاي من الـ Validator العادي) نحوله لتنسيقك
                        return [
                            'message' => $message,
                        ];
                    })];
                })->toArray();

                return response()->json([
                    'status'  => 'error', // وضع الحالة في البداية
                    'message' => 'Validation failed.',
                    'errors'  => $errors,
                ], 422);
            }
        });
    })->create();
