<?php

namespace App\Actions\Visitor;

use App\Actions\BaseAction;
use App\Http\Resources\EventResource;
use App\Mail\SupportMessageMail;
use App\Models\ExhibitionProfile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SendSupportMessageAction extends BaseAction
{
    public function execute(string $email, string $subject, string $message)
    {
        $exhibitionEmail = Cache::rememberForever('exhibition_contact_email', function () {
            return ExhibitionProfile::value('contact_email') ?? config('mail.from.address');
        });

        Mail::to($exhibitionEmail)
            ->send(new SupportMessageMail(
                $email,
                $subject,
                $message
            ));
    }
}
