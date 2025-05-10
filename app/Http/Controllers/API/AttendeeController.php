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
    // Create a new Attendee
    public function create(CreateRequest $request)
    {
        // Initialize response array and status code
        $res = [];
        $code = 200;

        try {
            // Instantiate the AttendeeService to handle business logic
            $attendeeService = new AttendeeService();
            // Call the service method to create a new attendee
            $attendee = $attendeeService->createAttendee($request);

            // Prepare the success response
            $res = [
                'success' => true,
                'message' => 'Success! New Attendee has been created successfully.',
                'data' => new AttendeeSingleResponse($attendee), // Return the attendee data as a resource
            ];
        } catch (\Exception $e) {
            // If an exception occurs, prepare the error response
            $res = [
                'success' => 'false',
                'errors' => [],  // Error details could be expanded if needed
                'message' => $e->getMessage(), // Exception message
            ];
            $code = 500; // Set response code to 500 for errors
        }

        // Return the JSON response with the appropriate status code
        return response()->json($res, $code);
    }

    // Update an existing Attendee's data
    public function update(UpdateRequest $request, Attendee $attendee)
    {
        // Initialize response array and status code
        $res = [];
        $code = 200;

        try {
            // Instantiate the AttendeeService to handle business logic
            $attendeeService = new AttendeeService();
            // Call the service method to update the attendee data
            $attendee = $attendeeService->updateAttendee($request, $attendee);

            // Prepare the success response
            $res = [
                'success' => true,
                'message' => 'Success! Attendee data has been updated successfully.',
                'data' => new AttendeeSingleResponse($attendee), // Return the updated attendee data as a resource
            ];
        } catch (\Exception $e) {
            // If an exception occurs, prepare the error response
            $res = [
                'success' => 'false',
                'errors' => [],
                'message' => $e->getMessage(),
            ];
            $code = 500; // Set response code to 500 for errors
        }

        // Return the JSON response with the appropriate status code
        return response()->json($res, $code);
    }

    // Show the details of a specific Attendee
    public function show(Attendee $attendee)
    {
        // Set default status code
        $code = 200;

        // Prepare the success response
        $res = [
            'success' => true,
            'message' => 'Success! Attendee data fetched successfully.',
            'data' => new AttendeeSingleResponse($attendee), // Return the attendee data as a resource
        ];

        // Return the JSON response with the appropriate status code
        return response()->json($res, $code);
    }
}
