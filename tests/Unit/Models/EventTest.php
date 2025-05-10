<?php

namespace Tests\Unit\Models;

use App\Models\Event;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_casts_event_date_as_date()
    {
        $event = Event::factory()->create([
            'event_date' => '2025-12-31',
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $event->event_date);
        $this->assertEquals('2025-12-31', $event->event_date->toDateString());
    }

    /** @test */
    public function it_has_many_bookings()
    {
        $event = Event::factory()->create();

        $booking1 = Booking::factory()->create(['event_id' => $event->id]);
        $booking2 = Booking::factory()->create(['event_id' => $event->id]);

        $this->assertCount(2, $event->bookings);
        $this->assertTrue($event->bookings->contains($booking1));
        $this->assertTrue($event->bookings->contains($booking2));
    }

    /** @test */
    public function it_filters_events_by_search_terms()
    {
        $event1 = Event::factory()->create(['name' => 'Music Concert', 'description' => 'A great music concert']);
        $event2 = Event::factory()->create(['name' => 'Art Exhibition', 'description' => 'An amazing art exhibition']);
        
        $search = 'Music';
        
        $events = Event::allColumnFilter($search)->get();

        $this->assertTrue($events->contains($event1));
        $this->assertFalse($events->contains($event2));
    }

    /** @test */
    public function it_checks_if_booking_is_full()
    {
        // Create an event with a capacity of 5
        $event = Event::factory()->create(['capacity' => 5]);

        // Create 5 bookings for the event
        Booking::factory()->count(5)->create(['event_id' => $event->id]);

        $this->assertTrue($event->isBookingFull());

        // Create one more booking for the event
        Booking::factory()->create(['event_id' => $event->id]);

        $this->assertFalse($event->isBookingFull());
    }

    /** @test */
    public function it_calculates_remaining_seats()
    {
        // Create an event with a capacity of 10
        $event = Event::factory()->create(['capacity' => 10]);

        // Create 3 bookings for the event
        Booking::factory()->count(3)->create(['event_id' => $event->id]);

        $this->assertEquals(7, $event->getRemainingSeats());

        // Create 7 more bookings for the event
        Booking::factory()->count(7)->create(['event_id' => $event->id]);

        $this->assertEquals(0, $event->getRemainingSeats());
    }
}
