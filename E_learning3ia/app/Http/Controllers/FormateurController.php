<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\UserCredentialsMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class FormateurController extends Controller
{
    public function index()
    {
        $formateurs = User::role('Formateur')->latest()->paginate(15);
        return view('admin_site.formateurs.index', compact('formateurs'));
    }

    public function create()
    {
        return view('admin_site.formateurs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'sexe' => 'required|string|in:Masculin,Feminin',
            'nationalite' => 'required|string|max:255',
            'tel1' => 'required|string|max:20',
            'tel2' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:users,email',
            'ville' => 'required|string|max:255',
            'cni' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $password = Str::random(10);
        $validated['password'] = Hash::make($password);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos_formateurs', 'public');
        }
        if ($request->hasFile('cni')) {
            $validated['cni'] = $request->file('cni')->store('cni_formateurs', 'public');
        }

        $formateur = User::create($validated);
        $formateur->assignRole('Formateur');

        Mail::to($formateur->email)->send(new UserCredentialsMail($formateur->name, $formateur->email, $password));

        return redirect()->route('dashboard.formateur.index')->with('success', 'Formateur créé. Ses identifiants ont été envoyés par email.');
    }

    public function show(User $formateur)
    {
        abort_if(!$formateur->hasRole('Formateur'), 404);
        $formateur->load('matieres');
        return view('admin_site.formateurs.show', compact('formateur'));
    }

    public function edit(User $formateur)
    {
        abort_if(!$formateur->hasRole('Formateur'), 404);
        return view('admin_site.formateurs.edit', compact('formateur'));
    }

    public function update(Request $request, User $formateur)
    {
        abort_if(!$formateur->hasRole('Formateur'), 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'sexe' => 'required|string|in:Masculin,Feminin',
            'nationalite' => 'required|string|max:255',
            'tel1' => 'required|string|max:20',
            'tel2' => 'nullable|string|max:20',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($formateur->id)],
            'ville' => 'required|string|max:255',
            'cni' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($formateur->photo) Storage::disk('public')->delete($formateur->photo);
            $validated['photo'] = $request->file('photo')->store('photos_formateurs', 'public');
        }
        if ($request->hasFile('cni')) {
            if ($formateur->cni) Storage::disk('public')->delete($formateur->cni);
            $validated['cni'] = $request->file('cni')->store('cni_formateurs', 'public');
        }

        $formateur->update($validated);

        return redirect()->route('dashboard.formateur.index')->with('success', 'Informations du formateur mises à jour.');
    }

    public function destroy(User $formateur)
    {
        abort_if(!$formateur->hasRole('Formateur'), 404);
        $formateur->delete();
        return redirect()->route('dashboard.formateur.index')->with('success', 'Formateur supprimé.');
    }
}
