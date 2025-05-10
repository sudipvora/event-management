<?php

namespace Tests\Unit\Controllers\API;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Attendee;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_booking()
    {
        // Create necessary data
        $event = Event::factory()->create();
        $attendee = Attendee::factory()->create();

        $bookingData = [
            'event_id' => $event->id,
            'attendee_id' => $attendee->id,
        ];

        // Mock the BookingService
        $bookingService = Mockery::mock(BookingService::class);
        $bookingService->shouldReceive('createBooking')
                        ->once()
                        ->with(Mockery::on(function ($arg) use ($bookingData) {
                            return $arg->event_id === $bookingData['event_id'] && $arg->attendee_id === $bookingData['attendee_id'];
                        }))
                        ->andReturn([
                            'booking' => new Booking([
                                'eventData' => $event,
                                'attendee' => $attendee,
                            ])
                        ]);

        $this->app->instance(BookingService::class, $bookingService);

        // Call the create method
        $response = $this->postJson(route('booking.create'), $bookingData);

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'success' => true,
            'message' => 'Success! Event has been created successfully.',
            'data' => [
                'event' => [
                    'id' => $event->id,
                    'name' => $event->name,
                    // Include other necessary event details here
                ],
                'attendee' => [
                    'id' => $attendee->id,
                    'name' => $attendee->name,
                    // Include other necessary attendee details here
                ],
            ]
        ]);
    }

    /** @test */
    public function it_returns_error_when_exception_occurs_during_booking_creation()
    {
        // Mock the BookingService to throw an exception
        $bookingService = Mockery::mock(BookingService::class);
        $bookingService->shouldReceive('createBooking')
                        ->once()
                        ->andThrow(new \Exception('Booking creation failed'));

        $this->app->instance(BookingService::class, $bookingService);

        // Call the create method with invalid data
        $response = $this->postJson(route('booking.create'), []);

        // Assert the response
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        $response->assertJson([
            'success' => 'false',
            'errors' => [],
            'message' => 'Booking creation failed',
        ]);
    }

    /** @test */
    public function it_validates_request_data_for_booking_creation()
    {
        // Call the create method with missing data
        $response = $this->postJson(route('booking.create'), []);

        // Assert that validation errors are returned
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['event_id', 'attendee_id']);
    }
}
