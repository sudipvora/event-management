<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Attendee\CreateRequest;
use App\Http\Requests\API\Attendee\UpdateRequest;
use App\Http\Resources\API\Attendee\AttendeeSingleResponse;
use App\Models\Attendee;
use App\Services\AttendeeService;

class AttendeeController extends Controller
{
    //
    public function create( CreateRequest $request ){
        $res = [];
        $code = 200;
        try{
            $attendeeService = new AttendeeService();
            $attendee = $attendeeService->createAttendee( $request );
            $res = [
                'success' => true,
                'message' => 'Success! New Attendee has been created successfully.',
                'data' => new AttendeeSingleResponse( $attendee )
            ];

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

    public function update( UpdateRequest $request, Attendee $attendee ){
        $res = [];
        $code = 200;
        try{
            $attendeeService = new AttendeeService();
            $attendee = $attendeeService->updateAttendee( $request, $attendee );
            $res = [
                'success' => true,
                'message' => 'Success! Attendee data has been updated successfully.',
                'data' => new AttendeeSingleResponse( $attendee )
            ];

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

    public function show( Attendee $attendee ){

        $code = 200;
        $res = [
            'success' => true,
            'message' => 'Success! Attendee data fetched successfully.',
            'data' => new AttendeeSingleResponse( $attendee )
        ];

        return response()->json( $res, $code );
    }
}
