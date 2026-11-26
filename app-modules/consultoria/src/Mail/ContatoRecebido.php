<?php

declare(strict_types=1);

namespace TresPontosTech\Consultoria\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContatoRecebido extends Mailable
{
    /**
     * @param  array{nome: string, email: string, empresa: ?string, interesse: string, mensagem: ?string, origem: ?string}  $lead
     */
    public function __construct(public array $lead, public string $consentimentoEm) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->lead['email'], $this->lead['nome'])],
            subject: '[Site Consultoria] '.$this->lead['interesse'].' - '.$this->lead['nome'],
        );
    }

    public function content(): Content
    {
        return new Content(text: 'consultoria::mail.contato');
    }
}
