<?php

namespace Tests\Feature\Api;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;

class EventControllerAdditionalTest extends ApiTestCase
{
    public function test_authenticated_user_can_check_in_to_event(): void
    {
        $admin = $this->createAdminUser();
        $user = User::factory()->create();
        $event = Event::factory()->create(['is_active' => true]);
        $registration = EventRegistration::factory()->create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => \App\EventRegistrationStatus::CONFIRMED,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson($this->apiUrl("events/{$event->id}/check-in"), [
                'ticket_code' => $registration->ticket_code,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
            ]);

        $registration->refresh();
        $this->assertTrue($registration->checked_in);
        $this->assertNotNull($registration->checked_in_at);
    }

    public function test_admin_can_view_event_registrations(): void
    {
        $admin = $this->createAdminUser();
        $event = Event::factory()->create(['is_active' => true]);
        EventRegistration::factory()->count(5)->create(['event_id' => $event->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson($this->apiUrl("events/{$event->id}/registrations"));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta',
            ]);
        $response->assertJsonCount(5, 'data');
    }

    public function test_regular_user_cannot_view_event_registrations(): void
    {
        $user = $this->createAuthenticatedUser();
        $event = Event::factory()->create(['is_active' => true]);

        $response = $this->getJson($this->apiUrl("events/{$event->id}/registrations"));

        $response->assertStatus(403);
    }
}
