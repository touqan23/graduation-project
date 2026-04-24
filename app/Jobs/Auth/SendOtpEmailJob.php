<?php

namespace App\Jobs\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendOtpEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 30;

    public function __construct(
        private readonly string $email,
        private readonly string $name,
        private readonly string $otp,
        private readonly int    $ttlMinutes,
    ) {}

    public function handle(): void
    {
        Mail::send([], [], function (Message $message) {
            $message
                ->to($this->email)
                ->subject('Your DIEMS Password Reset Code')
                ->html(
                    "<p>Hello {$this->name},</p>" .
                    "<p>Your password reset code is:</p>" .
                    "<h2 style='letter-spacing:8px;font-size:36px;font-family:monospace;color:#333'>" .
                    "{$this->otp}</h2>" .
                    "<p>This code expires in <strong>{$this->ttlMinutes} minutes</strong>.</p>" .
                    "<p>If you did not request this, please ignore this email.</p>"
                );
        });

        Log::info('[OTP] Email sent', ['email' => $this->email]);
    }

    public function failed(\Throwable $e): void
    {
        Log::error('[OTP] Email job failed', [
            'email' => $this->email,
            'error' => $e->getMessage(),
        ]);
    }
}
