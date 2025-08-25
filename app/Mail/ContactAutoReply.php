<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAutoReply extends Mailable
{
    use Queueable, SerializesModels;

    // public $contact;

    /**
     * Create a new message instance.
     *
     * @param mixed $contact
     * @return void
     */
    public function __construct(public Contact $contact)
    {
        // $this->contact = $contact;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Merci pour votre message'
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-auto-reply'
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Thank You for Contacting Us')
                    ->view('emails.contact-auto-reply');
    }
}
