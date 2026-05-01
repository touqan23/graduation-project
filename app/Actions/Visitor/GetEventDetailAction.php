<?php
// app/Actions/Visitor/GetEventDetailAction.php
namespace App\Actions\Visitor;

use App\Models\EventRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class GetEventDetailAction
{
    /**
     * تفاصيل الفعالية — TTL أطول لأنها تتغير نادراً بعد الـ approval.
     */
    public function execute(int $id): array
    {
        //Cache::forget("events:show:{$id}");
        $event = Cache::remember("events:show:{$id}", now()->addMinutes(30), function () use ($id) {
            return EventRequest::paid()
                ->with([
                    'slot:id,slot_date,start_time,end_time',
                    'sector:id,name_ar,name_en',
                    'hall:id,name',
                ])
                ->select([
                    'id', 'event_title', 'event_description',
                    'image', 'organizer_name',
                    'slot_id', 'sector_id', 'hall_id',
                ])
                ->findOrFail($id);
        });

        return [
            'id' => $event->id,
            'event_title' => $event->event_title,
            'event_description' => $event->event_description,
            'organizer_name' => $event->organizer_name,
            'image_url' => $event->image ? Storage::disk('s3')->url($event->image) : null,
            'slot_id' => $event->slot->id,
            'start_time' => $event->slot ? Carbon::parse($event->slot->start_time)->format('g A') : null,
            'end_time' => $event->slot ? Carbon::parse($event->slot->end_time)->format('g A') : null,
            'sector_id' => $event->sector_id,
            'sector_name' => $event->sector->name_ar,
            'hall_id' => $event->hall_id,
            'hall_name' => $event->hall->name,
        ];
    }
}
