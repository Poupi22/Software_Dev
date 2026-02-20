<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HomeSlide;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class FrontContactController extends Controller
{
    public function index()
    {
        $slides = HomeSlide::where('is_active', true)->orderBy('order')->get();
        $contactInfo = Contact::first();

        return view('home.contact', compact('slides', 'contactInfo'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'required|string|max:20',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        Mail::to('kingslydebruyne17@gmail.com')->send(new ContactFormMail($validated));

        // If your form submits normally (no AJAX), redirect back with a flash message:
        if (!$request->expectsJson()) {
            return redirect()->route('home.home.contact')->with('success', 'Votre message a été envoyé avec succès !');
        }

        // If you submit via AJAX, return JSON:
        return response()->json([
            'success' => true,
            'message' => 'Votre message a été envoyé avec succès !',
        ]);
    }
}
