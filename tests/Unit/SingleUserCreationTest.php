<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SingleUserCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creation of a single user.
     *
     * @return void
     */
    public function test_single_user_creation()
    {
        $user = User::factory()->create();
        $this->assertNotNull($user);
    }
}
