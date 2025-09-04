<?php

namespace App\Http\Controllers;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;
use App\Models\Temoignage;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use App\Mail\FrontMail;

class FrontContactController extends Controller
{
    public function index()
    {
        $contact = Contact::first();
                $temoignages = Temoignage::where('publie', true)
                                 ->latest()
                                 ->take(10)
                                 ->get();

        return view('acceuil.contact', compact('contact', 'temoignages'));
    }
    public function send(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email',
            'tel' => 'nullable|string',
            'sujet' => 'required|string',
            'message' => 'required|string',
        ]);

        // Données du formulaire
        $nom = $validated['nom'];
        $prenom = $validated['prenom'];
        $email = $validated['email'];
        $tel = $validated['tel'];
        $sujet = $validated['sujet'];
        $message = $validated['message'];

        try{
            Mail::to(config('mail.to_admin.address'))->send(new FrontMail($nom, $prenom, $email, $tel, $sujet, $message));
            return back()->with('success', 'Votre message a été envoyé avec succès !');
        } catch (Exception $e) {
            \Log::error('Contact form error', ['error' => $e->getMessage(), 'user_email' => $email]);
            return back()->with('error', "L'envoi du message a échoué. Veuillez réessayer plus tard.");
        }
}
}
