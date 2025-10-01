<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $url;
    /**
     * Create a new message instance.
     */
    public function __construct($token, $url)
    {
        //
        $this->token = $token;
        $this->url = $url;
    }

    /**
     * Get the message envelope.
     *   public function envelope(): Envelope
     *   {
     *       return new Envelope(
     *           subject: 'Invitation Token Mail',
     *       );
     *   }
     */

    /**
     * Get the message content definition.
     *   public function content(): Content
     *   {
     *       return new Content(
     *           view: 'view.name',
     *       );
     *  }
     */

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     *   public function attachments(): array
     *   {
     *       return [];
     *   }
     */

    public function build()
    {
        return $this->subject('Tu token de invitación')
            ->markdown('emails.invitation-token')
            ->with(['url' => $this->url]);
    }
}
