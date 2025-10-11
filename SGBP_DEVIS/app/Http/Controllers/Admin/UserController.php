<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Afficher la liste des utilisateurs
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Filtre par statut (utilise 'actif' au lieu de 'is_active')
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('actif', true);
            } elseif ($request->status === 'inactive') {
                $query->where('actif', false);
            }
        }

        // Tri
        $sortField = $request->get('sort', 'created_at_desc');
        
        switch ($sortField) {
            case 'nom_asc':
                $query->orderBy('nom', 'asc');
                break;
            case 'nom_desc':
                $query->orderBy('nom', 'desc');
                break;
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $users = $query->paginate(15);

        // Liste des rôles pour le filtre
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()],
            'telephone' => 'nullable|string|max:20',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'actif' => 'boolean',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'roles.required' => 'Au moins un rôle doit être assigné.',
            'roles.min' => 'Au moins un rôle doit être assigné.',
        ]);

        // Créer l'utilisateur
        $user = User::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'telephone' => $validated['telephone'] ?? null,
            'actif' => $request->boolean('actif', true),
        ]);

        // Assigner les rôles
        $user->syncRoles($validated['roles']);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "L'utilisateur {$user->nom_complet} a été créé avec succès.");
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function show(User $user)
    {
        $user->load('roles.permissions');
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(User $user)
    {
        // Empêcher la modification du super-admin par quelqu'un d'autre
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Vous n\'avez pas la permission de modifier le Super Admin.');
        }

        $roles = Role::orderBy('name')->get();
        $user->load('roles');

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        // Empêcher la modification du super-admin par quelqu'un d'autre
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Vous n\'avez pas la permission de modifier le Super Admin.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()],
            'telephone' => 'nullable|string|max:20',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'actif' => 'boolean',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'roles.required' => 'Au moins un rôle doit être assigné.',
            'roles.min' => 'Au moins un rôle doit être assigné.',
        ]);

        // Empêcher la désactivation du super-admin
        if ($user->isSuperAdmin() && !$request->boolean('actif')) {
            return redirect()
                ->back()
                ->with('error', 'Le compte Super Admin ne peut pas être désactivé.');
        }

        // Mettre à jour les informations
        $user->update([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'actif' => $request->boolean('actif', true),
        ]);

        // Mettre à jour le mot de passe si fourni
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);
        }

        // Synchroniser les rôles (sauf pour super-admin)
        if (!$user->isSuperAdmin()) {
            $user->syncRoles($validated['roles']);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', "L'utilisateur {$user->nom_complet} a été modifié avec succès.");
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy(User $user)
    {
        // Empêcher la suppression du super-admin
        if ($user->isSuperAdmin()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Le compte Super Admin ne peut pas être supprimé.');
        }

        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $nomComplet = $user->nom_complet;
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "L'utilisateur {$nomComplet} a été supprimé avec succès.");
    }

    /**
     * Activer/Désactiver un utilisateur
     */
    public function toggleStatus(User $user)
    {
        // Empêcher la désactivation du super-admin
        if ($user->isSuperAdmin()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Le compte Super Admin ne peut pas être désactivé.');
        }

        // Empêcher la désactivation de son propre compte
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $user->update([
            'actif' => !$user->actif
        ]);

        $status = $user->actif ? 'activé' : 'désactivé';

        return redirect()
            ->route('admin.users.index')
            ->with('success', "L'utilisateur {$user->nom_complet} a été {$status} avec succès.");
    }

    /**
     * Réinitialiser le mot de passe d'un utilisateur
     */
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()],
        ], [
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "Le mot de passe de {$user->nom_complet} a été réinitialisé avec succès.");
    }
}