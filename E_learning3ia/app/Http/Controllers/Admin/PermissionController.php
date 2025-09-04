<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Affiche la liste des permissions
     */
    public function index()
    {
        $permissions = Permission::when(request('search'), function($query) {
            $query->where('name', 'like', '%'.request('search').'%');
        })->paginate(10);

        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Affiche les détails d'une permission
     */
    public function show(Permission $permission)
    {
        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Enregistre une nouvelle permission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:permissions|max:255',
        ]);

        Permission::create(['name' => $validated['name']]);

        return redirect()->route('dashboard1.permission.index')
            ->with('success', 'Permission créée avec succès');
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', [
            'permission' => $permission,
            'availableGuards' => ['web', 'api'] // Exemple de données supplémentaires
        ]);
    }

    /**
     * Met à jour une permission existante
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',
                'unique:permissions,name,'.$permission->id
            ],
            'guard_name' => 'sometimes|in:web,api' // Optionnel
        ]);

        try {
            $permission->update($validated);

            return redirect()
                ->route('dashboard1.permission.index', $permission)
                ->with('success', 'Permission mise à jour avec succès');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour: '.$e->getMessage());
        }
    }

    /**
     * Supprime une permission
     */
    public function destroy(Permission $permission)
    {
        try {
            $permission->delete();
            return redirect()
                ->route('dashboard1.permission.index')
                ->with('success', 'Permission supprimée avec succès');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Erreur lors de la suppression: '.$e->getMessage());
        }
    }
}
