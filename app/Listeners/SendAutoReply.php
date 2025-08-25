<?php

namespace App\Listeners;

use App\Events\ContactSubmitted;
use App\Mail\ContactAutoReply;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendAutoReply implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 3;


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

        try {
            //code...
            Mail::to($event->contact->email)
                ->send(new ContactAutoReply($event->contact));
        } catch (\Exception $e ) {
            //throw $th;
            Log::error('Failed to send auto-reply', [
                'contact_id' => $event->contact->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
