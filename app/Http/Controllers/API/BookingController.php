<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Booking\CreateRequest;
use App\Http\Resources\API\Attendee\AttendeeSingleResponse;
use App\Http\Resources\API\Event\EventSingleResponse;
use App\Services\BookingService;

class BookingController extends Controller
{
    // Method to create a booking for an event
    public function create(CreateRequest $request)
    {
        // Initialize the response array and default status code
        $res = [];
        $code = 200;

        try {
            // Instantiate the BookingService to handle business logic
            $bookingService = new BookingService();
            // Call the service method to create a booking, passing in the request data
            $resData = $bookingService->createBooking($request);
            // Retrieve the booking data from the response
            $booking = data_get($resData, 'booking');
            
            // If booking data exists, prepare the success response
            if (!empty($booking)) {
                $res = [
                    'success' => true,
                    'message' => 'Success! Event has been created successfully.',
                    'data' => [
                        'event' => new EventSingleResponse($booking->eventData),
                        'attendee' => new AttendeeSingleResponse($booking->attendee),
                    ]
                ];
            }

        } catch (\Exception $e) {
            // If an exception occurs, prepare the error response
            $res = [
                'success' => 'false',
                'errors' => [],  // Errors can be expanded if needed
                'message' => $e->getMessage(),  // Include the exception message in the response
            ];
            $code = 500;  // Set response status code to 500 for errors
        }

        // Return the JSON response with the appropriate status code
        return response()->json($res, $code);
    }
}
