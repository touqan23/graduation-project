<?php

namespace App\Http\Controllers;

use App\Actions\Visitor\GetCompaniesSearchBar;
use App\Actions\Visitor\GetCompanyDetailAction;
use App\Actions\Visitor\GetEventDetailAction;
use App\Actions\Visitor\GetEventsByDateAction;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use ApiResponse;

    public function eventsByDate(Request $request,GetEventsByDateAction $action): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date_format:d-m-Y'],
        ]);
        $date = $validated['date'];
        $formatedDate = Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
        $events = $action->execute($formatedDate);

        return $this->success($events,
            'Events fetched successfully'
        );
    }

    public function eventDetails(int $id, GetEventDetailAction $action): JsonResponse
    {
        $event = $action->execute($id);

        return $this->success($event,
            'Event detail fetched successfully'
        );
    }


}
