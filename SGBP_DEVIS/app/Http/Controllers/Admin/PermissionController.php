<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    /**
     * Liste des permissions critiques qui ne peuvent pas être supprimées
     */
    protected $criticalPermissions = [
        'users.viewAny',
        'users.create',
        'users.update',
        'users.delete',
        'roles.viewAny',
        'roles.create',
        'roles.update',
        'roles.delete',
        'permissions.viewAny',
        'permissions.create',
        'permissions.update',
        'permissions.delete',
    ];

    /**
     * Afficher la liste des permissions
     */
    public function index(Request $request)
    {
        $query = Permission::query();

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Filtre par groupe (ressource)
        if ($request->filled('group')) {
            $group = $request->group;
            $query->where('name', 'like', "{$group}.%");
        }

        $permissions = $query->orderBy('name')->paginate(20);

        // Grouper les permissions par ressource
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'other';
        });

        // Liste des groupes disponibles pour le filtre
        $groups = Permission::all()->map(function ($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'other';
        })->unique()->sort()->values();

        return view('admin.permissions.index', compact('permissions', 'groupedPermissions', 'groups'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        // Suggestions de groupes existants
        $existingGroups = Permission::all()->map(function ($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0] ?? null;
        })->filter()->unique()->sort()->values();

        return view('admin.permissions.create', compact('existingGroups'));
    }

    /**
     * Enregistrer une nouvelle permission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:permissions,name',
                'regex:/^[a-z_]+\.[a-z_]+$/i'
            ],
            'guard_name' => 'required|string|in:web,api',
        ], [
            'name.required' => 'Le nom de la permission est obligatoire.',
            'name.unique' => 'Cette permission existe déjà.',
            'name.regex' => 'Le format doit être : ressource.action (ex: articles.create)',
            'guard_name.required' => 'Le guard est obligatoire.',
        ]);

        Permission::create($validated);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permission créée avec succès.');
    }

    /**
     * Afficher les détails d'une permission
     */
    public function show(Permission $permission)
    {
        // Récupérer tous les rôles qui ont cette permission
        $roles = Role::whereHas('permissions', function ($query) use ($permission) {
            $query->where('permissions.id', $permission->id);
        })->get();

        // Compter les utilisateurs ayant cette permission (via rôles)
        $usersCount = \App\Models\User::role($roles->pluck('name')->toArray())->count();

        return view('admin.permissions.show', compact('permission', 'roles', 'usersCount'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Permission $permission)
    {
        // Vérifier si c'est une permission critique
        $isCritical = in_array($permission->name, $this->criticalPermissions);

        // Suggestions de groupes existants
        $existingGroups = Permission::all()->map(function ($perm) {
            $parts = explode('.', $perm->name);
            return $parts[0] ?? null;
        })->filter()->unique()->sort()->values();

        return view('admin.permissions.edit', compact('permission', 'isCritical', 'existingGroups'));
    }

    /**
     * Mettre à jour une permission
     */
    public function update(Request $request, Permission $permission)
    {
        // Protection des permissions critiques
        if (in_array($permission->name, $this->criticalPermissions)) {
            return redirect()
                ->route('admin.permissions.index')
                ->with('error', 'Cette permission est critique et ne peut pas être modifiée.');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:permissions,name,' . $permission->id,
                'regex:/^[a-z_]+\.[a-z_]+$/i'
            ],
            'guard_name' => 'required|string|in:web,api',
        ], [
            'name.required' => 'Le nom de la permission est obligatoire.',
            'name.unique' => 'Cette permission existe déjà.',
            'name.regex' => 'Le format doit être : ressource.action (ex: articles.create)',
            'guard_name.required' => 'Le guard est obligatoire.',
        ]);

        $permission->update($validated);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permission modifiée avec succès.');
    }

    /**
     * Supprimer une permission
     */
    public function destroy(Permission $permission)
    {
        // Protection des permissions critiques
        if (in_array($permission->name, $this->criticalPermissions)) {
            return redirect()
                ->route('admin.permissions.index')
                ->with('error', 'Cette permission est critique et ne peut pas être supprimée.');
        }

        // Vérifier si la permission est utilisée par des rôles
        $rolesCount = $permission->roles()->count();

        if ($rolesCount > 0) {
            return redirect()
                ->route('admin.permissions.index')
                ->with('error', "Cette permission est utilisée par {$rolesCount} rôle(s) et ne peut pas être supprimée.");
        }

        $permission->delete();

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permission supprimée avec succès.');
    }

    /**
     * Synchroniser les permissions depuis le fichier config
     */
    public function sync()
    {
        $createdCount = 0;

        // Permissions CRUD depuis les ressources
        $resources = config('permissions.resources', []);
        foreach ($resources as $resource => $config) {
            foreach ($config['permissions'] as $action) {
                $permissionName = "{$resource}.{$action}";
                $created = Permission::firstOrCreate(
                    ['name' => $permissionName],
                    ['guard_name' => 'web']
                );
                if ($created->wasRecentlyCreated) $createdCount++;
            }
        }

        // Permissions spéciales
        $special = config('permissions.special_permissions', []);
        foreach ($special as $permissionName => $displayName) {
            $created = Permission::firstOrCreate(
                ['name' => $permissionName],
                ['guard_name' => 'web']
            );
            if ($created->wasRecentlyCreated) $createdCount++;
        }

        if ($createdCount > 0) {
            return redirect()
                ->route('admin.permissions.index')
                ->with('success', "{$createdCount} nouvelle(s) permission(s) synchronisée(s) depuis la configuration.");
        }

        return redirect()
            ->route('admin.permissions.index')
            ->with('info', 'Aucune nouvelle permission à synchroniser.');
    }
}
