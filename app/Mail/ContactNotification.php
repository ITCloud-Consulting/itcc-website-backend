<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Contact;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;


class ContactNotification extends Mailable
{
    use Queueable, SerializesModels;

    // public $contact;

    /**
     * Create a new message instance.
     *
     * @param mixed $contact
     * @return void
     */
    public function __construct(
        public Contact $contact
    )
    {
        // $this->contact = $contact;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Nouveau Contact: { $this->contact->subject }",
            replyTo: [$this->contact->email => $this->contact->name]
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-notification'
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Contact Form Submission')
                    ->view('emails.contact-notification');
    }
}
