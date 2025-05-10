<?php

namespace Tests\Unit\Controllers\API;

use App\Models\Attendee;
use App\Services\AttendeeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class AttendeeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_attendee()
    {
        // Mock the request data for creating an attendee
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ];

        // Mock the AttendeeService
        $attendeeService = Mockery::mock(AttendeeService::class);
        $attendeeService->shouldReceive('createAttendee')
                        ->once()
                        ->with(Mockery::on(function ($arg) use ($data) {
                            return $arg->name === $data['name'] && $arg->email === $data['email'];
                        }))
                        ->andReturn(new Attendee($data));

        $this->app->instance(AttendeeService::class, $attendeeService);

        // Call the create method
        $response = $this->postJson(route('attendees.store'), $data);

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'success' => true,
            'message' => 'Success! New Attendee has been created successfully.',
        ]);
    }

    /** @test */
    public function it_can_update_an_attendee()
    {
        // Create a dummy attendee
        $attendee = Attendee::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);

        // New data to update the attendee
        $newData = [
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
        ];

        // Mock the AttendeeService
        $attendeeService = Mockery::mock(AttendeeService::class);
        $attendeeService->shouldReceive('updateAttendee')
                        ->once()
                        ->with(Mockery::on(function ($request, $attendee) use ($newData) {
                            return $request->name === $newData['name'] && $request->email === $newData['email'];
                        }), $attendee)
                        ->andReturn($attendee->fill($newData));

        $this->app->instance(AttendeeService::class, $attendeeService);

        // Call the update method
        $response = $this->putJson(route('attendees.update', $attendee->id), $newData);

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'success' => true,
            'message' => 'Success! Attendee data has been updated successfully.',
        ]);
    }

    /** @test */
    public function it_can_show_an_attendee()
    {
        // Create a dummy attendee
        $attendee = Attendee::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);

        // Call the show method
        $response = $this->getJson(route('attendees.show', $attendee->id));

        // Assert the response
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'success' => true,
            'message' => 'Success! Attendee data fetched successfully.',
        ]);
    }

    /** @test */
    public function it_returns_error_when_exception_occurs_during_create()
    {
        // Mock the AttendeeService to throw an exception
        $attendeeService = Mockery::mock(AttendeeService::class);
        $attendeeService->shouldReceive('createAttendee')
                        ->once()
                        ->andThrow(new \Exception('Something went wrong'));

        $this->app->instance(AttendeeService::class, $attendeeService);

        // Call the create method
        $response = $this->postJson(route('attendees.store'), []);

        // Assert the response
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        $response->assertJson([
            'success' => 'false',
            'message' => 'Something went wrong',
        ]);
    }

    /** @test */
    public function it_returns_error_when_exception_occurs_during_update()
    {
        // Create a dummy attendee
        $attendee = Attendee::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);

        // Mock the AttendeeService to throw an exception
        $attendeeService = Mockery::mock(AttendeeService::class);
        $attendeeService->shouldReceive('updateAttendee')
                        ->once()
                        ->andThrow(new \Exception('Update failed'));

        $this->app->instance(AttendeeService::class, $attendeeService);

        // Call the update method
        $response = $this->putJson(route('attendees.update', $attendee->id), []);

        // Assert the response
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        $response->assertJson([
            'success' => 'false',
            'message' => 'Update failed',
        ]);
    }
}
