<?php

namespace Tests\Feature;

use Tests\TestCase;

class ChatifyIntegrationTest extends TestCase
{
    public function test_chatify_routes_are_registered(): void
    {
        $routes = collect(\Illuminate\Support\Facades\Route::getRoutes())->map(fn ($route) => $route->uri());

        $this->assertTrue($routes->contains('chatify'));
        $this->assertTrue($routes->contains('chatify/{id}'));
        $this->assertTrue($routes->contains('chatify/api/sendMessage'));
    }

    public function test_guest_user_cannot_access_chatify(): void
    {
        $response = $this->get('/chatify');

        $response->assertRedirect(route('login'));
    }
}
