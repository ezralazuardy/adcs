<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test update data of a single user.
     *
     * @return void
     */
    public function test_update_user(): void
    {
        $user = User::factory()->create();
        $user->update([
            "name" => "Ezra Lazuardy",
        ]);
        $this->assertEquals("Ezra Lazuardy", $user->fresh()->name);
    }
}
