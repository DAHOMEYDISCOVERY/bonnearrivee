<?php

namespace App\Mail;

use App\Models\RestoAccessToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WadunuMagicLink extends Mailable
{
    use Queueable, SerializesModels;

    public string $magicLink;
    public string $email;
    public string $expiresAt;

    public function __construct(RestoAccessToken $token)
    {
        $this->magicLink = url(route('wadunu.catalog', $token->token, false));
        $this->email     = $token->email;
        $this->expiresAt = $token->order->access_expires_at->translatedFormat('j F Y');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre accès Wa Dùnù — Carnet d\'adresses gourmandes du Bénin',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.wadunu-magic-link');
    }
}
