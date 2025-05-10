<?php

namespace Tests\Unit\Models;

use App\Models\Attendee;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendeeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_bookings()
    {
        $attendee = Attendee::factory()->create();

        $booking1 = Booking::factory()->create(['attendee_id' => $attendee->id]);
        $booking2 = Booking::factory()->create(['attendee_id' => $attendee->id]);

        $this->assertCount(2, $attendee->bookings);
        $this->assertTrue($attendee->bookings->contains($booking1));
        $this->assertTrue($attendee->bookings->contains($booking2));
    }

    /** @test */
    public function it_filters_attendees_by_search_terms()
    {
        $attendee1 = Attendee::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
        $attendee2 = Attendee::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);

        $search = 'John';

        $attendees = Attendee::allColumnFilter($search)->get();

        $this->assertTrue($attendees->contains($attendee1));
        $this->assertFalse($attendees->contains($attendee2));
    }

    /** @test */
    public function it_soft_deletes_attendee()
    {
        $attendee = Attendee::factory()->create();

        $this->assertNull($attendee->deleted_at);

        // Soft delete the attendee
        $attendee->delete();

        $this->assertNotNull($attendee->deleted_at);

        // Retrieve the attendee using withTrashed()
        $attendeeTrashed = Attendee::withTrashed()->find($attendee->id);
        $this->assertNotNull($attendeeTrashed->deleted_at);
    }
}
