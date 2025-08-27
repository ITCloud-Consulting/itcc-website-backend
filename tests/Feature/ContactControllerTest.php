<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Events\ContactSubmitted;
use App\Models\Contact;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;

class ContactControllerTest extends TestCase

{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        Queue::fake();
    }
    /**
     * A basic feature test example.
     */
    public function test_can_create_contact(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message with enough content.'
        ];

        $response = $this->postJson('/api/v1/contact', $data);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Votre message a été envoyé avec succès. Nous vous répondrons bientôt!'
                ]);

        $this->assertDatabaseHas('contacts', [
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message with enough content.'
        ]);

        Event::assertDispatched(ContactSubmitted::class);
    }

    public function test_contact_validation_fails_with_invalid_data(): void
    {
        $data = [
            'name' => 'A', // Trop court
            'email' => 'invalid-email', // Email invalide
            'subject' => 'Hi', // Trop court
            'message' => 'Short' // Trop court
        ];

        $response = $this->postJson('/api/v1/contact', $data);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'email', 'subject', 'message']);
    }

    public function test_contact_rate_limiting(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message with enough content.'
        ];

        // Faire 6 requêtes (limite = 5 par minute)
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('/api/v1/contact', $data);
            
            if ($i < 5) {
                $response->assertStatus(201);
            } else {
                $response->assertStatus(429); // Too Many Requests
            }
        }
    }
}
