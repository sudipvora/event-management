<?php

namespace Tests\Unit\Services;

use App\Models\Event;
use App\Services\EventService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class EventServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_event()
    {
        // Create a mock request
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')
                ->with('name')
                ->andReturn('Sample Event');
        $request->shouldReceive('input')
                ->with('description')
                ->andReturn('This is a sample event.');
        $request->shouldReceive('input')
                ->with('location')
                ->andReturn('Event Location');
        $request->shouldReceive('input')
                ->with('event_date')
                ->andReturn('2025-01-01');
        $request->shouldReceive('input')
                ->with('capacity')
                ->andReturn(100);

        $eventService = new EventService();

        // Call the createEvent method
        $event = $eventService->createEvent($request);

        // Assert event creation
        $this->assertNotNull($event);
        $this->assertEquals('Sample Event', $event->name);
        $this->assertEquals('This is a sample event.', $event->description);
    }

    /** @test */
    public function it_can_update_an_event()
    {
        // Create an event
        $event = Event::factory()->create();

        // Create a mock request for updating the event
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')
                ->with('name')
                ->andReturn('Updated Event');
        $request->shouldReceive('input')
                ->with('description')
                ->andReturn('Updated event description.');
        $request->shouldReceive('input')
                ->with('location')
                ->andReturn('Updated Location');
        $request->shouldReceive('input')
                ->with('event_date')
                ->andReturn('2025-02-01');
        $request->shouldReceive('input')
                ->with('capacity')
                ->andReturn(200);

        $eventService = new EventService();

        // Call the updateEvent method
        $updatedEvent = $eventService->updateEvent($request, $event);

        // Assert event updated
        $this->assertEquals('Updated Event', $updatedEvent->name);
        $this->assertEquals('Updated event description.', $updatedEvent->description);
    }

    /** @test */
    public function it_can_delete_an_event()
    {
        // Create an event
        $event = Event::factory()->create();

        $eventService = new EventService();

        // Call the deleteEvent method
        $result = $eventService->deleteEvent($event);

        // Assert event deleted
        $this->assertTrue($result);
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    /** @test */
    public function it_can_get_events_with_filters_and_pagination()
    {
        // Create multiple events
        Event::factory()->count(5)->create();

        // Create a mock request
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')
                ->with('sort_by')
                ->andReturn('name');
        $request->shouldReceive('input')
                ->with('sort_dir')
                ->andReturn('asc');
        $request->shouldReceive('input')
                ->with('search')
                ->andReturn('Sample');

        $eventService = new EventService();

        // Call the getEvents method
        $events = $eventService->getEvents($request);

        // Assert that events are returned and are paginated
        $this->assertCount(5, $events);
        $this->assertEquals('name', $events->getCollection()->first()->name);
    }

    /** @test */
    public function it_handles_errors_during_event_creation_and_update()
    {
        // Create a mock request with missing data for event creation
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')
                ->with('name')
                ->andReturn(null); // Missing name

        $eventService = new EventService();

        // Call the createEvent method and assert exception is thrown
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Name field is required.');

        $eventService->createEvent($request);
    }
}
