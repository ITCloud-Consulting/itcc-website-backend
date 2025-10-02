<?php

namespace App\Listeners;

use App\Events\ContactSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\ContactNotification;
use Illuminate\Support\Facades\Mail;    
use Illuminate\Support\Facades\Log;

class SendContactNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 3;
    public $backoff = [30, 60, 120]; // seconds

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
            $adminEmail = 'support@itcloudconsultings.com';
            Mail::to($adminEmail)
                ->send(new ContactNotification($event->contact));
            $event->contact->update([
                'status' => 'processed'
            ]);
            Log::info('Contact notification sent successfully: ', [
                'contact_id' => $event->contact->id,
                'admin_email' => $adminEmail,
                'message' => $event->contact->message
            ]); 
        } catch (\Exception $e) {
            Log::error('Contact notification failed: ', [
                'contact_id' => $event->contact->id,
                'error' => $e->getMessage()
            ]);
            $event->contact->update([
                'status' => 'failed'
            ]);
            $this->release(60); 
            throw $e;
        }
    }

    public function failed(ContactSubmitted $event, \Throwable $exception): void
    {
        Log::critical('Contact notification failed permanenetly', [
            'contact_id' => $event->contact->id,
            'error' => $exception->getMessage()
        ]);
    }
}
