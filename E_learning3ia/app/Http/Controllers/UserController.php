<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\UserCredentialsMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage; // Importez la façade Storage
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index()
    {
        $users = User::whereIsStaff()->with('roles')->latest()->paginate(15);
        return view('admin_site.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::whereNotIn('name', ['Etudiant', 'Formateur'])->get();
        return view('admin_site.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $password = Str::random(10);
        $validated['password'] = Hash::make($password);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('user_photos', 'public');
        }

        $user = User::create($validated);
        $user->assignRole($validated['roles']);
        try {
            Mail::to($user->email)->send(new UserCredentialsMail($user->name, $user->email, $password));
        } catch (\Exception $e) {
            \Log::error('Erreur d\'envoi d\'email à ' . $user->email . ': ' . $e->getMessage());
        }

        return redirect()->route('dashboard.user.index')->with('success', 'Utilisateur créé. Ses identifiants ont été envoyés par email.');
    }

    public function show(User $user)
    {
        return view('admin_site.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        if ($user->hasRole(['Etudiant', 'Formateur'])) {
            abort(404);
        }

        $roles = Role::whereNotIn('name', ['Etudiant', 'Formateur'])->get();
        $user->load('roles');
        return view('admin_site.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg|max:5120',
            'remove_photo' => 'nullable|boolean',
        ]);

        if ($request->has('remove_photo') && $user->photo) {
            Storage::disk('public')->delete($user->photo);
            $validated['photo'] = null;
        }

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $validated['photo'] = $request->file('photo')->store('user_photos', 'public');
        }

        $user->update($validated);
        $user->syncRoles($validated['roles']);

        return redirect()->route('dashboard.user.index')->with('success', 'Utilisateur mis à jour.');
    }


    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        if ($user->id === 1 && $user->hasRole('Administrateur')) {
            return back()->with('error', 'Impossible de supprimer l\'utilisateur super-administrateur.');
        }

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();
        return redirect()->route('dashboard.user.index')->with('success', 'Utilisateur supprimé.');
    }

}
