<?php

// app/Services/SmsService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send(string $phone, string $message): bool
    {
        $response = Http::timeout(10)->asForm()->post('https://www.cloud.smschef.com/api/send/sms', [
            'secret' => env('SMSCHEF_SECRET'),
            "mode" => "devices",
            'device' => env('DEVICE_ID'),
            'phone' => $phone,
            'message' => $message,
            "sim" => 1,
        ]);

        if (! $response->successful()) {
            Log::warning('[SMS] Failed to send', [
                'phone'  => $phone,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return false;
        }

        return true;
    }
}
