<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ===== GÉNÉRER AUTOMATIQUEMENT LES PERMISSIONS CRUD =====
        $resources = config('permissions.resources');

        foreach ($resources as $resource => $config) {
            foreach ($config['permissions'] as $action) {
                $permissionName = "{$resource}.{$action}";

                // ✅ MAPPING DES ACTIONS VERS NOMS FRANÇAIS
                $actionLabels = [
                    'create' => 'Créer',
                    'read' => 'Voir',
                    'update' => 'Modifier',
                    'delete' => 'Supprimer',
                ];

                $displayName = $actionLabels[$action] . ' ' . strtolower($config['display_name']);

                Permission::firstOrCreate(
                    ['name' => $permissionName],
                    [
                        'guard_name' => 'web',
                        'display_name' => $displayName, // ✅ AJOUTER LE NOM D'AFFICHAGE
                    ]
                );
            }
        }

        // ===== AJOUTER LES PERMISSIONS SPÉCIALES AVEC DISPLAY_NAME =====
        $specialPermissions = config('permissions.special_permissions');

        foreach ($specialPermissions as $permissionName => $displayName) {
            Permission::firstOrCreate(
                ['name' => $permissionName],
                [
                    'guard_name' => 'web',
                    'display_name' => $displayName, // ✅ UTILISER LE DISPLAY_NAME DU CONFIG
                ]
            );
        }

        // ===== CRÉER LE RÔLE SUPER ADMIN =====
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super-admin'],
            ['guard_name' => 'web']
        );

        // Donner TOUTES les permissions au Super Admin
        $superAdmin->syncPermissions(Permission::all());

        // ===== CRÉER L'UTILISATEUR SUPER ADMIN =====
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@exemple.cm'],
            [
                'nom' => 'Super',
                'prenom' => 'Admin',
                'password' => Hash::make(env('SUPER_ADMIN_PASSWORD', 'SuperAdmin@2026')),
                'telephone' => '+237 6 00 00 00 00',
                'actif' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assigner le rôle Super Admin
        $superAdminUser->assignRole('super-admin');

        $this->command->info('✅ Permissions créées : ' . Permission::count());
        $this->command->info('✅ Super Admin créé : superadmin@exemple.cm');
        $this->command->info('🔑 Mot de passe : SuperAdmin@2026');
    }
}
