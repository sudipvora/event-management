<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventService {


    public function getEvents ( $request, $perPage = 20 ){

        $orderBy = $request->input('sort_by', 'updated_at');
        $orderDir = $request->input('sort_dir', 'desc');

        return Event::withCount('bookings')
                    ->allColumnFilter( $request->input('search') )
                    ->orderBy( $orderBy, $orderDir )
                    ->paginate( $perPage );
    }


    public function createEvent( $request ){
        DB::beginTransaction();
        $event = new Event();
        $event = $this->createOrUpdateEvent( $request, $event );
        DB::commit();
        return $event;
    }

    public function updateEvent( $request, $event ){
        DB::beginTransaction();
        $event = $this->createOrUpdateEvent( $request, $event );
        DB::commit();
        return $event;
    }

    public function deleteEvent( $event ){
        DB::beginTransaction();
        $event->delete();
        DB::commit();
        return true;
    }

    private function createOrUpdateEvent( $request, $event ){
        $event->name = $request->input('name');
        $event->description = $request->input('description');
        $event->location = $request->input('location');
        $event->event_date = $request->input('event_date');
        $event->capacity = $request->input('capacity');
        $event->save();

        return $event;
    }
}
