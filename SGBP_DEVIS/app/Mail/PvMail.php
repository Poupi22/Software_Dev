<?php

namespace App\Mail;

use App\Models\Parametre;
use App\Models\Pv;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PvMail extends Mailable
{
    use Queueable, SerializesModels;

    public Pv $pv;
    public ?string $customMessage;

    public function __construct(Pv $pv, ?string $customMessage = null)
    {
        $this->pv = $pv;
        $this->customMessage = $customMessage;
    }

    public function envelope(): Envelope
    {
        $parametre = Parametre::first();
        $entreprise = $parametre->nom_entreprise ?? config('app.name');
        $from = $parametre->email_expediteur ?? null;

        return new Envelope(
            from: $from ? new \Illuminate\Mail\Mailables\Address($from, $entreprise) : null,
            subject: "PV de Réception {$this->pv->numero} — {$entreprise}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pv',
            with: [
                'pv' => $this->pv,
                'parametre' => Parametre::first(),
                'customMessage' => $this->customMessage,
            ],
        );
    }

    public function attachments(): array
    {
        $this->pv->load('facture.client', 'facture.devis', 'client');
        $parametre = Parametre::first();

        $pdf = Pdf::loadView('admin.pvs.pdf', [
            'pv' => $this->pv,
            'parametre' => $parametre,
        ]);

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                "PV-{$this->pv->numero}.pdf"
            )->withMime('application/pdf'),
        ];
    }
}
