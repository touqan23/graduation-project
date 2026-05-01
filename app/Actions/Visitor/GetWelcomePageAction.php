<?php

namespace App\Actions\Visitor;

use App\Http\Resources\ExhibitionProfileResource;
use App\Models\ExhibitionProfile;
use App\Models\GlobalSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class GetWelcomePageAction
{
    public function execute()
    {
        $lang = app()->getLocale();

        return Cache::rememberForever("exhibition_profile_{$lang}", function () {
            $profile = ExhibitionProfile::first();

            if (!$profile) {
                return null;
            }
            return (new ExhibitionProfileResource($profile))->resolve();
        });
    }
}
