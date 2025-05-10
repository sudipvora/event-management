<?php

namespace Tests\Unit\Models;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_event()
    {
        $event = Event::factory()->create();
        $booking = Booking::factory()->create(['event_id' => $event->id]);

        $this->assertInstanceOf(Event::class, $booking->eventData);
        $this->assertEquals($event->id, $booking->eventData->id);
    }

    /** @test */
    public function it_belongs_to_attendee()
    {
        $attendee = Attendee::factory()->create();
        $booking = Booking::factory()->create(['attendee_id' => $attendee->id]);

        $this->assertInstanceOf(Attendee::class, $booking->attendee);
        $this->assertEquals($attendee->id, $booking->attendee->id);
    }

    /** @test */
    public function it_soft_deletes_booking()
    {
        $booking = Booking::factory()->create();

        $this->assertNull($booking->deleted_at);

        // Soft delete the booking
        $booking->delete();

        $this->assertNotNull($booking->deleted_at);

        // Retrieve the booking using withTrashed()
        $bookingTrashed = Booking::withTrashed()->find($booking->id);
        $this->assertNotNull($bookingTrashed->deleted_at);
    }
}
