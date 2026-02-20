<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        // IMPORTANT: use the emails.* view, NOT home.contact
        return $this->subject('Nouveau message du formulaire de contact')
                    ->view('emails.contact')
                    ->with(['data' => $this->data]);
    }
}
