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
    // Method to retrieve a list of events with pagination
    public function getList(Request $request)
    {
        // Instantiate the EventService to handle event logic
        $eventService = new EventService();
        // Call the service method to get events with the requested pagination
        $events = $eventService->getEvents($request, $request->input('per_page', 20));

        // Wrap the events data in the PaginationResponse resource to standardize the output
        $res = new PaginationResponse($events);

        // Return the paginated events list as a JSON response with a 200 status code
        return response()->json($res, 200);
    }

    // Method to create a new event
    public function create(CreateRequest $request)
    {
        // Initialize the response array and default status code
        $res = [];
        $code = 200;

        try {
            // Instantiate the EventService to handle event creation
            $eventService = new EventService();
            // Call the service method to create a new event with the provided data
            $event = $eventService->createEvent($request);
            // Prepare a successful response with event data wrapped in the EventSingleResponse resource
            $res = [
                'success' => true,
                'message' => 'Success! Event has been created successfully.',
                'data' => new EventSingleResponse($event)  // Event data wrapped in a resource
            ];

        } catch (\Exception $e) {
            // In case of an error, prepare the error response
            $res = [
                'success' => 'false',
                'errors' => [],  // Empty errors array, can be expanded if necessary
                'message' => $e->getMessage()  // Include the exception message in the response
            ];
            $code = 500;  // Set status code to 500 for server error
        }

        // Return the response in JSON format with the appropriate status code
        return response()->json($res, $code);
    }

    // Method to update an existing event
    public function update(UpdateRequest $request, Event $event)
    {
        // Initialize the response array and default status code
        $res = [];
        $code = 200;

        try {
            // Instantiate the EventService to handle event updates
            $eventService = new EventService();
            // Call the service method to update the event with the provided data
            $event = $eventService->updateEvent($request, $event);
            // Prepare a successful response with updated event data wrapped in the EventSingleResponse resource
            $res = [
                'success' => true,
                'message' => 'Success! Event data has been updated successfully.',
                'data' => new EventSingleResponse($event)  // Updated event data wrapped in a resource
            ];

        } catch (\Exception $e) {
            // In case of an error, prepare the error response
            $res = [
                'success' => 'false',
                'errors' => [],  // Empty errors array, can be expanded if necessary
                'message' => $e->getMessage()  // Include the exception message in the response
            ];
            $code = 500;  // Set status code to 500 for server error
        }

        // Return the response in JSON format with the appropriate status code
        return response()->json($res, $code);
    }

    // Method to delete an event
    public function delete(Event $event)
    {
        // Initialize the response array and default status code
        $res = [];
        $code = 200;

        try {
            // Instantiate the EventService to handle event deletion
            $eventService = new EventService();
            // Call the service method to delete the event
            $event = $eventService->deleteEvent($event);
            // Prepare a successful response with a confirmation message
            $res = [
                'success' => true,
                'message' => 'Success! Event data has been removed successfully.',
                'data' => (object)[]  // Empty data object, as no additional data is returned
            ];

        } catch (\Exception $e) {
            // In case of an error, prepare the error response
            $res = [
                'success' => 'false',
                'errors' => [],  // Empty errors array, can be expanded if necessary
                'message' => $e->getMessage()  // Include the exception message in the response
            ];
            $code = 500;  // Set status code to 500 for server error
        }

        // Return the response in JSON format with the appropriate status code
        return response()->json($res, $code);
    }
}
