<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionHelper
{
    /**
     * Génère les permissions CRUD pour toutes les tables de la base de données,
     * en évitant de recréer celles qui existent déjà.
     * Assigne les nouvelles permissions au rôle 'admin'.
     */
    public static function generateCrudPermissionsForAllTables(): void
    {
        // Obtenir toutes les tables dans la base de données
        $tables = DB::select('SHOW TABLES');
        $key = 'Tables_in_' . DB::getDatabaseName();

        // Obtenir toutes les permissions déjà existantes
        $existingPermissions = Permission::pluck('name')->toArray();

        // Obtenir le rôle admin
        $admin = Role::where('name', 'admin')->first();

        foreach ($tables as $table) {
            if (!isset($table->$key)) continue;

            $tableName = $table->$key;

            // Exclure certaines tables système
            if (in_array($tableName, [
                'migrations',
                'password_reset_tokens',
                'failed_jobs',
                'personal_access_tokens',
                'users', 
                'roles',
                'permissions',
                'model_has_permissions',
                'model_has_roles',
                'role_has_permissions'
            ])) {
                continue;
            }

            // Définir les actions CRUD
            $actions = ['create', 'read', 'update', 'delete'];
            $newPermissions = [];

            foreach ($actions as $action) {
                $permissionName = "{$action} {$tableName}";

                if (!in_array($permissionName, $existingPermissions)) {
                    // Créer la permission si elle n'existe pas encore
                    $permission = Permission::create(['name' => $permissionName]);
                    $newPermissions[] = $permission->name;
                }
            }

            // Donner les nouvelles permissions à l'admin
            if ($admin && count($newPermissions)) {
                $admin->givePermissionTo($newPermissions);
            }
        }
    }
}
