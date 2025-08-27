<?php

namespace App\DTOs;

use Illuminate\Http\Request;

readonly class ContactDTO
{
    public function __construct (
        public string $name,
        public string $email,
        public string $subject,
        public string $message,
        public array $metadata = [],
        
    ){}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->validated('name'),
            email: $request->validated('email'),
            subject: $request->validated('subject'),
            message: $request->validated('message'),
            metadata: [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'submitted_at' => now()->toISOString(),
            ]
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
            'metadata' => $this->metadata
        ];
    }
}