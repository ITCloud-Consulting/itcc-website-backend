<?php
// tests/Unit/ContactServiceTest.php

namespace Tests\Unit;

use App\DTOs\ContactDTO;
use App\Events\ContactSubmitted;
use App\Services\ContactService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ContactServiceTest extends TestCase
{
    use RefreshDatabase;

    private ContactService $contactService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->contactService = new ContactService();
        Event::fake();
    }

    public function test_can_create_contact(): void
    {
        $dto = new ContactDTO(
            name: 'John Doe',
            email: 'john@example.com',
            subject: 'Test Subject',
            message: 'This is a test message',
            metadata: ['ip' => '127.0.0.1']
        );

        $contact = $this->contactService->createContact($dto);

        $this->assertNotNull($contact->id);
        $this->assertEquals('John Doe', $contact->name);
        $this->assertEquals('john@example.com', $contact->email);
        
        Event::assertDispatched(ContactSubmitted::class);
    }
}