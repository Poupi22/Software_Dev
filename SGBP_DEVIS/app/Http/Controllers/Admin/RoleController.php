<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    // Liste des rôles
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    // Formulaire création
    public function create()
    {
        // Grouper les permissions par resource
        $resources = config('permissions.resources');
        $specialPermissions = config('permissions.special_permissions');
        
        $groupedPermissions = [];
        
        foreach ($resources as $resource => $config) {
            $groupedPermissions[$resource] = [
                'display_name' => $config['display_name'],
                'icon' => $config['icon'],
                'permissions' => Permission::where('name', 'like', "{$resource}.%")->get()
            ];
        }
        
        // Permissions spéciales
        $specialPerms = Permission::whereIn('name', array_keys($specialPermissions))->get();
        
        return view('admin.roles.create', compact('groupedPermissions', 'specialPerms'));
    }

    // Enregistrer
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle créé avec succès !');
    }

    // Formulaire édition
    public function edit(Role $role)
    {
        // Empêcher modification du Super Admin
        if ($role->name === 'super-admin') {
            abort(403, 'Le rôle Super Admin ne peut pas être modifié.');
        }

        $resources = config('permissions.resources');
        $specialPermissions = config('permissions.special_permissions');
        
        $groupedPermissions = [];
        
        foreach ($resources as $resource => $config) {
            $groupedPermissions[$resource] = [
                'display_name' => $config['display_name'],
                'icon' => $config['icon'],
                'permissions' => Permission::where('name', 'like', "{$resource}.%")->get()
            ];
        }
        
        $specialPerms = Permission::whereIn('name', array_keys($specialPermissions))->get();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        return view('admin.roles.edit', compact('role', 'groupedPermissions', 'specialPerms', 'rolePermissions'));
    }

    // Mettre à jour
    public function update(Request $request, Role $role)
    {
        if ($role->name === 'super-admin') {
            abort(403, 'Le rôle Super Admin ne peut pas être modifié.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle mis à jour avec succès !');
    }

    // Supprimer
    public function destroy(Role $role)
    {
        if ($role->name === 'super-admin') {
            abort(403, 'Le rôle Super Admin ne peut pas être supprimé.');
        }

        // Vérifier si des utilisateurs ont ce rôle
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un rôle attribué à des utilisateurs.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle supprimé avec succès !');
    }
}