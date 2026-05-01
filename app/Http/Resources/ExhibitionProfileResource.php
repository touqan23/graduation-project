<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExhibitionProfileResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = app()->getLocale();

        return [
            'id'      => $this->id,
            'name'    => $this->name[$lang] ?? null,
            'session' => $this->session,
            'address' => $this->address[$lang] ?? null,
            'bio'     => $this->bio[$lang] ?? null,
            'dates' => [
                'start' => $this->start_date,
                'end'   => $this->end_date,
            ],
            'working_hours' => [
                'open'  => $this->open_time? Carbon::parse($this->open_time)->format('g A') : null,
                'close' => $this->close_time? Carbon::parse($this->close_time)->format('g A') : null,
            ],
            'contact' => [
                'email' => $this->contact_email,
                'phone' => $this->contact_phone,
            ],
            'social' => [
                'facebook'  => $this->facebook_url,
                'instagram' => $this->instagram_url,
                'x'         => $this->x_url,
            ],
            'welcome_page' => [
                'title' => $this->title,
                'logo'  => $this->syria_logo ? Storage::disk('s3')->url($this->syria_logo) : null,
                'video' => $this->welcome_video ? Storage::disk('s3')->url($this->welcome_video) : null,
            ],
        ];
    }
}
