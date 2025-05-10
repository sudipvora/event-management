<?php

namespace Tests\Unit\Services;

use App\Models\Booking;
use App\Models\Event;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_booking_when_event_has_available_capacity()
    {
        // Create an event with available capacity
        $event = Event::factory()->create(['capacity' => 10]);
        $attendee = Attendee::factory()->create(); // Assuming Attendee factory exists

        // Create a mock request
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')
                ->with('event_id')
                ->andReturn($event->id);
        $request->shouldReceive('input')
                ->with('attendee_id')
                ->andReturn($attendee->id);

        $bookingService = new BookingService();

        // Call the createBooking method
        $result = $bookingService->createBooking($request);

        // Assert booking creation
        $this->assertTrue($result['success']);
        $this->assertNotNull($result['booking']);
        $this->assertNull($result['errorMsg']);
    }

    /** @test */
    public function it_fails_to_create_booking_if_booking_already_exists()
    {
        // Create an event and an attendee
        $event = Event::factory()->create();
        $attendee = Attendee::factory()->create();

        // Create an existing booking
        Booking::factory()->create(['event_id' => $event->id, 'attendee_id' => $attendee->id]);

        // Create a mock request
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')
                ->with('event_id')
                ->andReturn($event->id);
        $request->shouldReceive('input')
                ->with('attendee_id')
                ->andReturn($attendee->id);

        $bookingService = new BookingService();

        // Call the createBooking method and assert exception is thrown
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Oops! Already booking exists for the requested data.');

        $bookingService->createBooking($request);
    }

    /** @test */
    public function it_fails_to_create_booking_if_event_is_full()
    {
        // Create an event with no available capacity
        $event = Event::factory()->create(['capacity' => 1]);
        $attendee1 = Attendee::factory()->create();
        $attendee2 = Attendee::factory()->create();

        // Create a booking for the first attendee
        Booking::factory()->create(['event_id' => $event->id, 'attendee_id' => $attendee1->id]);

        // Create a mock request for the second attendee
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')
                ->with('event_id')
                ->andReturn($event->id);
        $request->shouldReceive('input')
                ->with('attendee_id')
                ->andReturn($attendee2->id);

        $bookingService = new BookingService();

        // Call the createBooking method and assert exception is thrown
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Oops! There is no available seat as event became full.');

        $bookingService->createBooking($request);
    }

    /** @test */
    public function it_fails_to_create_booking_if_event_does_not_exist()
    {
        // Create an attendee
        $attendee = Attendee::factory()->create();

        // Create a mock request for a non-existent event
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')
                ->with('event_id')
                ->andReturn(9999);  // Non-existent event ID
        $request->shouldReceive('input')
                ->with('attendee_id')
                ->andReturn($attendee->id);

        $bookingService = new BookingService();

        // Call the createBooking method and assert exception is thrown
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Oops! Event not available.');

        $bookingService->createBooking($request);
    }

    /** @test */
    public function it_handles_errors_gracefully_when_creating_booking()
    {
        // Create a mock request for a non-existent event
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')
                ->with('event_id')
                ->andReturn(null);  // Invalid event ID

        $bookingService = new BookingService();

        // Call the createBooking method and assert exception is thrown
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Oops! Event not available.');

        $bookingService->createBooking($request);
    }
}
