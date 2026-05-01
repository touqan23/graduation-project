<?php

namespace App\Actions\Visitor;

use App\Models\EventRequest;
use App\Models\EventSlot;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class GetEventsByDateAction
{
    /**
     * جلب الفعاليات حسب التاريخ مع كامل العلاقات في query واحد.
     * Cache لكل تاريخ منفصل — TTL قصير لأن الأحداث قد تُعتمد خلال اليوم.
     */
    public function execute(string $date): Collection
    {
        //Cache::forget("events:by_date:{$date}");
        $events = Cache::remember("events:by_date:{$date}", now()->addMinutes(10), function () use ($date) {
            return EventRequest::query()
                ->paid()
                ->with([
                    'slot:id,slot_date,start_time,end_time',
                    'sector:id,name_ar,name_en',
                    'hall:id,name',
                ])
                ->forDate($date)
                ->select(['id', 'event_title', 'image', 'slot_id', 'sector_id', 'hall_id'])
                ->orderBy(
                    EventSlot::select('start_time')
                        ->whereColumn('event_slots.id', 'event_requests.slot_id')
                        ->limit(1)
                )
                ->get();
        });
        return $events->map(function ($event) {
            return [
                'id'          => $event->id,
                'event_title' => $event->event_title,
                'image_url'   => $event->image ? Storage::disk('s3')->url($event->image) : null,
                'slot_id'     => $event->slot->id,
                'start_time'  => $event->slot ? Carbon::parse($event->slot->start_time)->format('g A') : null,
                'end_time'    =>$event->slot ? Carbon::parse($event->slot->end_time)->format('g A') : null,
                'sector_id'   => $event->sector_id,
                'sector_name' => $event->sector->name_ar,
                'hall_id'     => $event->hall_id,
                'hall_name'   => $event->hall->name,
            ];
        });
    }
}
