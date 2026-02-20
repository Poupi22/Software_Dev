<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();
        return view('admin.contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('admin.contacts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required',
            'telephone' => 'required',
            'heuredouverture' => 'required',
            'location' => 'required',
            'email' => 'required|email',
        ]);

        Contact::create($request->all());

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact created successfully.');
    }

    public function show(Contact $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        return view('admin.contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'address' => 'required',
            'telephone' => 'required',
            'heuredouverture' => 'required',
            'location' => 'required',
            'email' => 'required|email',
        ]);

        $contact->update($request->all());

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact updated successfully');
    }
    

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact deleted successfully');
    }
}