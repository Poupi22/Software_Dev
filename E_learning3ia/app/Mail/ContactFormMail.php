<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nom;
    public $prenom;
    public $email;
    public $sujet;
    public $message;
    public $fichier;

    public function __construct($nom, $prenom, $email, $sujet, $message, $fichier = null)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->sujet = $sujet;
        $this->message = $message;
        $this->fichier = $fichier;
    }


    public function build()
    {
        return $this->subject($this->sujet)->markdown('emails.contact-form') 
                    ->when($this->fichier, function ($mail, $fichier) {
                        $mail->attach($fichier->getRealPath(), [
                            'as' => $fichier->getClientOriginalName(),
                            'mime' => $fichier->getMimeType(),
                        ]);
                    });
}
}
