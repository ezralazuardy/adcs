<?php

/** @noinspection PhpUndefinedClassInspection */

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrowserSessionsTest extends TestCase
{
    use RefreshDatabase;

    public function testOtherBrowserSessionsCanBeLoggedOut(): void
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->delete("/user/other-browser-sessions", [
            "password" => $user->email,
        ]);

        $response->assertSessionHasNoErrors();
    }
}
