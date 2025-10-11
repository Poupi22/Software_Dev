<?php

namespace App\Mail;

use App\Models\Facture;
use App\Models\Parametre;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FactureMail extends Mailable
{
    use Queueable, SerializesModels;

    public Facture $facture;
    public ?string $customMessage;

    public function __construct(Facture $facture, ?string $customMessage = null)
    {
        $this->facture = $facture;
        $this->customMessage = $customMessage;
    }

    public function envelope(): Envelope
    {
        $parametre = Parametre::first();
        $entreprise = $parametre->nom_entreprise ?? config('app.name');
        $from = $parametre->email_expediteur ?? null;

        return new Envelope(
            from: $from ? new \Illuminate\Mail\Mailables\Address($from, $entreprise) : null,
            subject: "Facture {$this->facture->numero} — {$entreprise}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.facture',
            with: [
                'facture' => $this->facture,
                'parametre' => Parametre::first(),
                'customMessage' => $this->customMessage,
            ],
        );
    }

    public function attachments(): array
    {
        $this->facture->load('client', 'articles.article', 'categories');
        $parametre = Parametre::first();

        $pdf = Pdf::loadView('admin.factures.pdf', [
            'facture' => $this->facture,
            'parametre' => $parametre,
        ]);

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(
                fn () => $pdf->output(),
                "Facture-{$this->facture->numero}.pdf"
            )->withMime('application/pdf'),
        ];
    }
}
