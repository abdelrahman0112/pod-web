<?php

namespace Tests\Feature;

use Tests\TestCase;

class ChatifyIntegrationTest extends TestCase
{
    public function test_chatify_routes_are_registered(): void
    {
        $routes = collect(\Illuminate\Support\Facades\Route::getRoutes())->map(fn ($route) => $route->uri());

        $this->assertTrue($routes->contains('chat'), 'Route "chat" not found');
        $this->assertTrue($routes->contains('chat/{id}'), 'Route "chat/{id}" not found');
        $this->assertTrue($routes->contains('chat/api/sendMessage'), 'Route "chat/api/sendMessage" not found');
    }

    public function test_guest_user_cannot_access_chatify(): void
    {
        $response = $this->get('/chat');

        $response->assertRedirect(route('login'));
    }
}
