<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;

PermissionHelper::generateCrudPermissionsForAllTables();

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:roles,name',
        'permissions' => 'array'
    ]);

    $role = Role::create([
        'name' => $request->name,
        'guard_name' => 'web',
    ]);

    if ($request->has('permissions')) {
        $permissionNames = Permission::whereIn('id', $request->permissions)->pluck('name');
        $role->syncPermissions($permissionNames);
    }

    return redirect()->route('dashboard1.role.index')
        ->with('success', 'Rôle créé avec succès');
}


    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
{
    $validated = $request->validate([
        'name' => 'required|max:255|unique:roles,name,' . $role->id,
        'permissions' => 'array',
        'permissions.*' => 'exists:permissions,id',
    ]);

    // Mise à jour du nom du rôle
    $role->update(['name' => $validated['name']]);

    // Convertir les IDs en noms de permissions
    $permissionNames = Permission::whereIn('id', $validated['permissions'] ?? [])->pluck('name')->toArray();

    // Synchronisation des permissions par nom
    $role->syncPermissions($permissionNames);

    return redirect()->route('dashboard1.role.index')->with('success', 'Rôle mis à jour avec succès.');
}

    public function show($id)
{
    $role = Role::findOrFail($id); // Récupère le rôle par ID

    // Récupère toutes les permissions assignées à ce rôle
    $permissions = $role->permissions;

    return view('admin.roles.show', compact('role', 'permissions'));
}

    public function destroy(Role $role)
    {
        $role->delete();
        return back();
    }
}
