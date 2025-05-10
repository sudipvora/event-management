<?php

namespace Tests\Unit\Controllers\API;

use App\Models\Event;
use App\Services\EventService;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_a_list_of_events()
    {
        // Create some events
        Event::factory()->count(5)->create();

        // Mock the EventService to return paginated events
        $eventService = Mockery::mock(EventService::class);
        $eventService->shouldReceive('getEvents')
                     ->once()
                     ->andReturn(Event::paginate(20));

        $this->app->instance(EventService::class, $eventService);

        // Call the getList method
        $response = $this->getJson(route('event.getList'));

        // Assert that the response is successful and contains paginated events
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'current_page', 'data', 'first_page_url', 'last_page_url', 'total'
            ]
        ]);
    }

    /** @test */
    public function it_can_create_an_event()
    {
        $eventData = [
            'name' => 'New Event',
            'description' => 'Event description',
            // Add other required fields for event creation
        ];

        // Mock the EventService to return the created event
        $eventService = Mockery::mock(EventService::class);
        $eventService->shouldReceive('createEvent')
                     ->once()
                     ->with(Mockery::on(function ($arg) use ($eventData) {
                         return $arg->name === $eventData['name'];
                     }))
                     ->andReturn(new Event($eventData));

        $this->app->instance(EventService::class, $eventService);

        // Call the create method
        $response = $this->postJson(route('event.create'), $eventData);

        // Assert the response is successful and contains the created event data
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'success' => true,
            'message' => 'Success! Event has been created successfully.',
            'data' => [
                'name' => $eventData['name'],
                'description' => $eventData['description'],
                // Include other necessary fields for event
            ]
        ]);
    }

    /** @test */
    public function it_can_update_an_event()
    {
        $event = Event::factory()->create();

        $updatedData = [
            'name' => 'Updated Event Name',
            'description' => 'Updated description',
            // Add other fields that might be updated
        ];

        // Mock the EventService to return the updated event
        $eventService = Mockery::mock(EventService::class);
        $eventService->shouldReceive('updateEvent')
                     ->once()
                     ->with(Mockery::on(function ($arg1, $arg2) use ($event, $updatedData) {
                         return $arg1->name === $updatedData['name'] && $arg2->id === $event->id;
                     }))
                     ->andReturn(new Event($updatedData));

        $this->app->instance(EventService::class, $eventService);

        // Call the update method
        $response = $this->putJson(route('event.update', $event->id), $updatedData);

        // Assert the response is successful and contains the updated event data
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'success' => true,
            'message' => 'Success! Event data has been updated successfully.',
            'data' => [
                'name' => $updatedData['name'],
                'description' => $updatedData['description'],
                // Include other necessary fields for event
            ]
        ]);
    }

    /** @test */
    public function it_can_delete_an_event()
    {
        $event = Event::factory()->create();

        // Mock the EventService to simulate the event deletion
        $eventService = Mockery::mock(EventService::class);
        $eventService->shouldReceive('deleteEvent')
                     ->once()
                     ->with($event)
                     ->andReturn(true);

        $this->app->instance(EventService::class, $eventService);

        // Call the delete method
        $response = $this->deleteJson(route('event.delete', $event->id));

        // Assert the response is successful and the event has been deleted
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'success' => true,
            'message' => 'Success! Event data has been removed successfully.',
            'data' => (object)[], // Empty data as no additional data is returned
        ]);
    }

    /** @test */
    public function it_returns_error_when_exception_occurs_while_creating_event()
    {
        $eventData = ['name' => 'Faulty Event'];

        // Mock the EventService to throw an exception
        $eventService = Mockery::mock(EventService::class);
        $eventService->shouldReceive('createEvent')
                     ->once()
                     ->andThrow(new \Exception('Event creation failed'));

        $this->app->instance(EventService::class, $eventService);

        // Call the create method with faulty data
        $response = $this->postJson(route('event.create'), $eventData);

        // Assert the error response
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        $response->assertJson([
            'success' => 'false',
            'errors' => [],
            'message' => 'Event creation failed',
        ]);
    }
}
