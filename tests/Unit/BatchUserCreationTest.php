<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BatchUserCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creation of a batch user.
     *
     * @return void
     */
    public function test_batch_user_creation()
    {
        for ($i = 0; $i < 20; $i++) {
            User::factory()->create();
        }
        $this->assertTrue(User::count() >= 20);
    }
}
