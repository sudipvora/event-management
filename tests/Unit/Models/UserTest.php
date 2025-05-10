<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_mass_assignable_attributes()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'secret',
        ];

        $user = User::create($userData);

        $this->assertEquals($user->name, 'John Doe');
        $this->assertEquals($user->email, 'john.doe@example.com');
        $this->assertTrue(Hash::check('secret', $user->password)); // Check that password is hashed
    }

    /** @test */
    public function it_hides_password_and_remember_token_in_serialization()
    {
        $user = User::factory()->create();

        // Convert user to array and check if sensitive attributes are hidden
        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayNotHasKey('remember_token', $userArray);
    }

    /** @test */
    public function it_casts_email_verified_at_to_datetime()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $user->email_verified_at);
        $this->assertTrue($user->email_verified_at->isToday());
    }

    /** @test */
    public function it_hashes_password()
    {
        $user = User::factory()->create([
            'password' => 'plain-text-password',
        ]);

        $this->assertTrue(Hash::check('plain-text-password', $user->password));
    }
}
