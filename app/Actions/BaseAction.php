<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Exception;

abstract class BaseAction
{
    /**
     * تنفيذ الأكشن داخل Transaction + (اختياري) تسجيل Activity
     */
    protected function executeAction(
        callable $callback,
        string $message = '',
        array $properties = [],
        bool $shouldLog = false //هاد ضفتو شمان حل مشكلة تكرار السطور الي كانت بجدول الاكتيفيتي لوق بحيث انو مو شرط كل مرا بدي ترانساكشن يعني بدي لوق
    ) {
        return DB::transaction(function () use ($callback, $message, $properties, $shouldLog) {
            try {
                $result = $callback();
                //اذا بعتت للتابع راسلة مع ترو يعني انا بدي سجل الحدث باللوق
                if ($shouldLog && !empty($message)) {
                    $subject = $result instanceof Model ? $result : null;

                    $this->recordActivity($message, $subject, $properties);
                }

                return $result;

            } catch (Exception $e) {
                Log::error("خطأ في تنفيذ الأكشن [" . get_class($this) . "]: " . $e->getMessage());
                throw $e;
            }
        });
    }


    protected function recordActivity(string $message, ?Model $subject, array $properties): void
    {
        activity()
            ->useLog('actions')
            ->event($properties['event_type'] ?? $this->guessEventType())
            ->causedBy(auth()->user())
            ->performedOn($subject)
            ->withProperties($properties)
            ->log($message);
    }

    /**
     * تخمين نوع الحدث
     */
    private function guessEventType(): string
    {
        $className = strtolower(class_basename($this));

        return match (true) {
            str_contains($className, 'create') || str_contains($className, 'store')  => 'created',
            str_contains($className, 'update') || str_contains($className, 'edit')   => 'updated',
            str_contains($className, 'delete') || str_contains($className, 'destroy') => 'deleted',
            default => 'notified',
        };
    }
}
