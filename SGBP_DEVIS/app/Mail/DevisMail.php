<?php

namespace App\Mail;

use App\Models\Devis;
use App\Models\Parametre;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DevisMail extends Mailable
{
    use Queueable, SerializesModels;

    public Devis $devis;
    public ?string $customMessage;

    public function __construct(Devis $devis, ?string $customMessage = null)
    {
        $this->devis = $devis;
        $this->customMessage = $customMessage;
    }

    public function envelope(): Envelope
    {
        $parametre = Parametre::first();
        $entreprise = $parametre->nom_entreprise ?? config('app.name');
        $from = $parametre->email_expediteur ?? null;

        return new Envelope(
            from: $from ? new \Illuminate\Mail\Mailables\Address($from, $entreprise) : null,
            subject: "Devis {$this->devis->numero} — {$entreprise}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.devis',
            with: [
                'devis' => $this->devis,
                'parametre' => Parametre::first(),
                'customMessage' => $this->customMessage,
            ],
        );
    }

    public function attachments(): array
    {
        $this->devis->load('client', 'articles.article', 'categories');
        $parametre = Parametre::first();

        $pdf = Pdf::loadView('admin.devis.pdf', [
            'devis' => $this->devis,
            'parametre' => $parametre,
        ]);

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(
                fn () => $pdf->output(),
                "Devis-{$this->devis->numero}.pdf"
            )->withMime('application/pdf'),
        ];
    }
}
