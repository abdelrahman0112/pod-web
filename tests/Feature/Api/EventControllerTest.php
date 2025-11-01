<?php

namespace Tests\Feature\Api;

use App\Models\Event;
use App\Models\EventRegistration;

class EventControllerTest extends ApiTestCase
{
    public function test_guest_can_view_events(): void
    {
        Event::factory()->count(5)->create(['is_active' => true]);

        $response = $this->getJson($this->apiUrl('events'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta',
                'links',
            ]);
    }

    public function test_guest_can_view_single_event(): void
    {
        $event = Event::factory()->create(['is_active' => true]);

        $response = $this->getJson($this->apiUrl("events/{$event->id}"));

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $event->id);
    }

    public function test_authenticated_user_can_create_event(): void
    {
        $user = $this->createClientUser();
        $category = \App\Models\EventCategory::factory()->create(['is_active' => true]);

        $startDate = now()->addWeek();
        $endDate = $startDate->copy()->addDay();
        $registrationDeadline = $startDate->copy()->subDay();

        $response = $this->postJson($this->apiUrl('events'), [
            'title' => 'Test Event',
            'description' => 'This is a test event',
            'start_date' => $startDate->toISOString(),
            'end_date' => $endDate->toISOString(),
            'location' => 'Test Location',
            'format' => 'in-person',
            'registration_deadline' => $registrationDeadline->toISOString(),
            'category_id' => $category->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
            ]);

        $this->assertDatabaseHas('events', [
            'title' => 'Test Event',
            'created_by' => $user->id,
        ]);
    }

    public function test_authenticated_user_can_register_for_event(): void
    {
        $user = $this->createAuthenticatedUser();
        $event = Event::factory()->create([
            'is_active' => true,
            'registration_deadline' => now()->addWeek(),
            'start_date' => now()->addWeeks(2),
            'max_attendees' => 100,
        ]);

        $response = $this->postJson($this->apiUrl("events/{$event->id}/register"));

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
            ]);

        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_authenticated_user_can_cancel_registration(): void
    {
        $user = $this->createAuthenticatedUser();
        $event = Event::factory()->create(['is_active' => true]);
        $registration = EventRegistration::factory()->create([
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);

        $response = $this->deleteJson($this->apiUrl("events/{$event->id}/register"));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('event_registrations', [
            'id' => $registration->id,
        ]);
    }
}
