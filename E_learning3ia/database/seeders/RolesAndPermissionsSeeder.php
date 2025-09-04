<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use App\Helpers\PermissionHelper;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // 1. Vider le cache des permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Créer ou retrouver le rôle administrateur
        $adminRole = Role::firstOrCreate(['name' => 'Administrateur']);
        $this->command->info('Role "Administrateur" ready.');

        // 3. Générer dynamiquement les permissions CRUD pour toutes les tables
        PermissionHelper::generateCrudPermissionsForAllTables();
        $this->command->info('CRUD permissions generated.');

        // 4. Assigner TOUTES les permissions au rôle admin
        $permissions = Permission::all();
        $adminRole->syncPermissions($permissions);
        $this->command->info('All permissions assigned to admin role.');

        // 5. Créer ou retrouver un utilisateur admin
        $user = User::first();
        if (!$user) {
            $this->command->warn('No user found. Creating default admin user...');
            
            // VERSION CORRIGÉE QUI N'UTILISE PAS FAKER
            $user = User::create([
                'name' => 'Rayan Ngoune',
                'email' => '3ia@institut3ia.com',
                'password' => bcrypt('institut3ia'),
                // Ajoutez ici d'autres champs obligatoires qui pourraient être dans votre factory
                // Par exemple, si email_verified_at est nécessaire :
                'email_verified_at' => now(), 
            ]);

            $this->command->info('Default admin user created: 3ia@institut3ia.com');
        }

        // 6. Assigner le rôle à l'utilisateur (si pas encore)
        if (!$user->hasRole($adminRole)) {
            $user->assignRole($adminRole);
            $this->command->info('Admin role assigned to user: ' . $user->email);
        }
    }
}