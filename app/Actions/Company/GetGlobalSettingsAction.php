<?php

namespace App\Actions\Company;

use App\Models\globalsetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class GetGlobalSettingsAction
{
    public function execute()
    {
        return Cache::rememberForever('global_settings', function () {
            return globalsetting::all()->groupBy('group')->map(function ($items) {
                return $items->keyBy('key')->map(function ($setting) {
                    return $this->formatValue($setting);
                });
            });
        });
    }

    private function formatValue($setting)
    {
        // معالجة الروابط لتعود كروابط S3 كاملة
        if (in_array($setting->type, ['image', 'video'])) {
            return $setting->value ? Storage::disk('s3')->url($setting->value) : null;
        }

        if ($setting->type === 'json') {
            return json_decode($setting->value, true);
        }

        return $setting->value;
    }
}
