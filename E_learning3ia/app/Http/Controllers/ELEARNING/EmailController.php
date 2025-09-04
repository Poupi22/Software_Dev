<?php

namespace App\Http\Controllers\Elearning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class EmailController extends Controller
{
    public function sendContactForm(Request $request)
    {
        // Validation des données reçues
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'sujet' => 'required|string|max:255',
            'message' => 'required|string',
            'fichier' => 'nullable|file|max:10240', // max 10MB
        ]);


            $nom = $validated['nom'];
            $prenom = $validated['prenom'];
            $email = $validated['email'];
            $sujet = $validated['sujet'];
            $message = $validated['message'];
            $fichier = $request->file('fichier') ;

        // Ajout de la pièce jointe si elle existe
        if ($request->hasFile('fichier')) {
            $email->attach(
                $request->file('fichier')->getRealPath(),
                [
                    'as' => $request->file('fichier')->getClientOriginalName(),
                    'mime' => $request->file('fichier')->getMimeType(),
                ]
            );
        }

        // Envoi de l'email à l'adresse définie

        Mail::to(config('mail.to_admin.address'))->send(new ContactFormMail($nom, $prenom, $email, $sujet, $message, $fichier));

        // Redirection avec message de succès
        return back()->with('success', 'Message envoyé avec succès !');
    }
}
