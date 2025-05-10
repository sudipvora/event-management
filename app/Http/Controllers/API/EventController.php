<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Event\CreateRequest;
use App\Http\Requests\API\Event\UpdateRequest;
use App\Http\Resources\API\Event\EventSingleResponse;
use App\Http\Resources\API\Event\PaginationResponse;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    //

    public function getList( Request $request ){
        $eventService = new EventService();
        $events = $eventService->getEvents( $request, $request->input('per_page', 20) );

        $res = new PaginationResponse( $events );

        return response()->json( $res, 200 );
    }

    public function create( CreateRequest $request ){
        $res = [];
        $code = 200;
        try{
            $eventService = new EventService();
            $event = $eventService->createEvent( $request );
            $res = [
                'success' => true,
                'message' => 'Success! Event has been created successfully.',
                'data' => new EventSingleResponse( $event )
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

    public function update( UpdateRequest $request, Event $event ){
        $res = [];
        $code = 200;
        try{
            $eventService = new EventService();
            $event = $eventService->updateEvent( $request, $event );
            $res = [
                'success' => true,
                'message' => 'Success! Event data has been updated successfully.',
                'data' => new EventSingleResponse( $event )
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

    public function delete( Event $event ){

        $res = [];
        $code = 200;

        try{
            $eventService = new EventService();
            $event = $eventService->deleteEvent( $event );
            $res = [
                'success' => true,
                'message' => 'Success! Event data has been removed successfully.',
                'data' => (object)[]
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
}
