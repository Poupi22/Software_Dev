<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FrontMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nom;
    public $prenom;
    public $email;
    public $tel;
    public $sujet;
    public $message;

    public function __construct($nom, $prenom, $email, $tel, $sujet, $message)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->tel = $tel;
        $this->sujet = $sujet;
        $this->message = $message;
    }

    public function build()
    {
        return $this
            ->subject($this->sujet)
            ->markdown('emails.contact-form');
    }
}
