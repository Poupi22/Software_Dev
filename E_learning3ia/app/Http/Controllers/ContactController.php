<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(10);
        return view('admin_site.contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('admin_site.contacts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'adresse' => 'required|string',
            'telephone' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'whatsapp' => 'required|string|max:255',
            'iframe_localisation' => 'required|string',
            'facebook_link' => 'nullable|url',
            'tiktok_link' => 'nullable|url',
            'linkedin_link' => 'nullable|url'
        ]);

        Contact::create($request->all());

        return redirect()->route('dashboard.contact.index')
                         ->with('success', 'Contact créé avec succès');
    }

    public function show(Contact $contact)
    {
        return view('admin_site.contacts.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        return view('admin_site.contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'adresse' => 'required|string',
            'telephone' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'whatsapp' => 'required|string|max:255',
            'iframe_localisation' => 'required|string',
            'facebook_link' => 'nullable|url',
            'tiktok_link' => 'nullable|url',
            'linkedin_link' => 'nullable|url'
        ]);

        $contact->update($request->all());

        return redirect()->route('dashboard.contact.index')
                         ->with('success', 'Contact mis à jour avec succès');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('dashboard.contact.index')
                         ->with('success', 'Contact supprimé avec succès');
    }
}
