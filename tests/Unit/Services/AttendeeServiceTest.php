<?php

namespace Tests\Unit\Services;

use App\Models\Attendee;
use App\Services\AttendeeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class AttendeeServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_a_list_of_attendees_with_pagination()
    {
        // Create some attendees
        Attendee::factory()->count(5)->create();

        // Mock the request input to simulate search and pagination
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')
                ->with('sort_by', 'updated_at')
                ->andReturn('updated_at');
        $request->shouldReceive('input')
                ->with('sort_dir', 'desc')
                ->andReturn('desc');
        $request->shouldReceive('input')
                ->with('search')
                ->andReturn(null);

        $attendeeService = new AttendeeService();
        $attendees = $attendeeService->getList($request);

        // Assert that the returned result is paginated and contains attendees
        $this->assertEquals(5, $attendees->count());
        $this->assertEquals(1, $attendees->currentPage());
    }

    /** @test */
    public function it_can_create_an_attendee()
    {
        // Mock request data for creating an attendee
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')
                ->with('name')
                ->andReturn('John Doe');
        $request->shouldReceive('input')
                ->with('email')
                ->andReturn('johndoe@example.com');

        // Mock the DB transaction to commit successfully
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $attendeeService = new AttendeeService();
        $attendee = $attendeeService->createAttendee($request);

        // Assert that the attendee has been created with the correct data
        $this->assertEquals('John Doe', $attendee->name);
        $this->assertEquals('johndoe@example.com', $attendee->email);
    }

    /** @test */
    public function it_can_update_an_attendee()
    {
        // Create an attendee
        $attendee = Attendee::factory()->create();

        // Mock request data for updating the attendee
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')
                ->with('name')
                ->andReturn('Jane Doe');
        $request->shouldReceive('input')
                ->with('email')
                ->andReturn('janedoe@example.com');

        // Mock the DB transaction to commit successfully
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $attendeeService = new AttendeeService();
        $updatedAttendee = $attendeeService->updateAttendee($request, $attendee);

        // Assert that the attendee's details have been updated
        $this->assertEquals('Jane Doe', $updatedAttendee->name);
        $this->assertEquals('janedoe@example.com', $updatedAttendee->email);
    }

    /** @test */
    public function it_creates_or_updates_an_attendee()
    {
        // Mock request data for creating or updating an attendee
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')
                ->with('name')
                ->andReturn('Alice Doe');
        $request->shouldReceive('input')
                ->with('email')
                ->andReturn('alicedoe@example.com');

        // Create a new attendee instance to pass into the method
        $attendee = new Attendee();

        $attendeeService = new AttendeeService();
        $updatedAttendee = $attendeeService->createOrUpdateAttendee($request, $attendee);

        // Assert that the attendee's details have been updated
        $this->assertEquals('Alice Doe', $updatedAttendee->name);
        $this->assertEquals('alicedoe@example.com', $updatedAttendee->email);
    }

    /** @test */
    public function it_rolls_back_transaction_if_error_occurs_while_creating_attendee()
    {
        // Mock request data for creating an attendee
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')
                ->with('name')
                ->andReturn('John Doe');
        $request->shouldReceive('input')
                ->with('email')
                ->andReturn('johndoe@example.com');

        // Mock the DB transaction to throw an exception
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->never();
        DB::shouldReceive('rollBack')->once();

        $attendeeService = new AttendeeService();

        // Simulate an exception to trigger a rollback
        $this->expectException(\Exception::class);

        $attendeeService->createAttendee($request);
    }

    /** @test */
    public function it_rolls_back_transaction_if_error_occurs_while_updating_attendee()
    {
        // Create an attendee
        $attendee = Attendee::factory()->create();

        // Mock request data for updating an attendee
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')
                ->with('name')
                ->andReturn('Jane Doe');
        $request->shouldReceive('input')
                ->with('email')
                ->andReturn('janedoe@example.com');

        // Mock the DB transaction to throw an exception
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->never();
        DB::shouldReceive('rollBack')->once();

        $attendeeService = new AttendeeService();

        // Simulate an exception to trigger a rollback
        $this->expectException(\Exception::class);

        $attendeeService->updateAttendee($request, $attendee);
    }
}
