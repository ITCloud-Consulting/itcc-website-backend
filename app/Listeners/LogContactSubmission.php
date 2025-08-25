<?php

namespace App\Listeners;

use App\Events\ContactSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogContactSubmission
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ContactSubmitted $event): void
    {
        //
        Log::info('Contact form submitted', [
            'contact_id' => $event->contact->id,
            'email' => $event->contact->email,
            'subject' => $event->contact->subject,
            'ip' => $event->requestMetadata['ip'] ?? 'N/A',
            'user_agent' => $event->requestMetadata['user_agent'] ?? 'N/A',
            'message' => $event->contact->message
        ]);
    }
}
