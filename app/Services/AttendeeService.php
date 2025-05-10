<?php

namespace App\Services;

use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class AttendeeService {


    public function getList ( $request, $perPage = 20 ){

        $orderBy = $request->input('sort_by', 'updated_at');
        $orderDir = $request->input('sort_dir', 'desc');

        return Attendee::allColumnFilter( $request->input('search') )
                            ->orderBy( $orderBy, $orderDir )
                            ->paginate( $perPage );
    }


    public function createAttendee ( $request ){
        DB::beginTransaction();
        $attendee = new Attendee();
        $attendee = $this->createOrUpdateAttendee( $request, $attendee );
        DB::commit();
        return $attendee;
    }

    public function updateAttendee( $request, $attendee ){
        DB::beginTransaction();
        $attendee = $this->createOrUpdateAttendee( $request, $attendee );
        DB::commit();
        return $attendee;
    }

    private function createOrUpdateAttendee( $request, $attendee ){
        $attendee->name = $request->input('name');
        $attendee->email = $request->input('email');
        $attendee->save();

        return $attendee;
    }
}
