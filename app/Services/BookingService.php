<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Event;
use Exception;
use Illuminate\Support\Facades\DB;

class BookingService {


    public function createBooking ( $request ){
        $eventId = $request->input('event_id');
        $attendeeId = $request->input('attendee_id');

        $success = false;
        $booking = null;
        $isError = false;
        $errorMsg = null;

        $booking = Booking::where('event_id', $eventId)->where('attendee_id', $attendeeId)->first();
        if( is_null( $booking ) ){
            $event = Event::withCount('bookings')->find( $eventId );
            if( !is_null( $event ) ){
                if( $event->bookings_count < $event->capacity ){
                    $booking = new Booking();
                    $booking->event_id = $eventId;
                    $booking->attendee_id = $attendeeId;
                    $booking->save();
                    $success = true;
                }else{
                    $isError = true;
                    $errorMsg = 'Oops! There is no available seat as event became full.';

                }
            }else{
                $isError = true;
                $errorMsg = 'Oops! Event not available.';

            }
        }else{
            $isError = true;
            $errorMsg = 'Oops! Already booking exists for the requested data.';
        }

        if( $isError ){
            throw new Exception( $errorMsg, 500 );
        }

        return compact( 'booking', 'success', 'isError', 'errorMsg' );
    }


}
