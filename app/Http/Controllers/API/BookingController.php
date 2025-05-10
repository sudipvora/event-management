<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Booking\CreateRequest;
use App\Http\Resources\API\Attendee\AttendeeSingleResponse;
use App\Http\Resources\API\Event\EventSingleResponse;
use App\Services\BookingService;

class BookingController extends Controller
{
    //


    public function create( CreateRequest $request ){
        $res = [];
        $code = 200;
        try{
            $bookingService = new BookingService();
            $resData = $bookingService->createBooking( $request );
            $booking = data_get( $resData, 'booking');
            if( !empty( $booking ) ){

                $res = [
                    'success' => true,
                    'message' => 'Success! Event has been created successfully.',
                    'data' => [
                        'event' => new EventSingleResponse( $booking->eventData ),
                        'attendee' => new AttendeeSingleResponse( $booking->attendee ),
                    ]
                ];
            }

        }catch(\Exception $e ){
            $res = [
                'success' => 'false',
                'errors' => [],
                'message' => $e->getMessage()
            ];
            $code = 500;
        }


        return response()->json( $res, $code );
    }
}
