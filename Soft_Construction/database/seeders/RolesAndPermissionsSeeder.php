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
        // 1. Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Create or retrieve roles
        $adminRole = Role::firstOrCreate(['name' => 'administrator']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $userRole = Role::firstOrCreate(['name' => 'user']);
        
        $this->command->info('Roles created: administrator, editor, user');

        // 3. Generate CRUD permissions for all tables
        PermissionHelper::generateCrudPermissionsForAllTables();
        $this->command->info('CRUD permissions generated for all tables.');

        // 4. Assign all permissions to admin role
        $permissions = Permission::all();
        $adminRole->syncPermissions($permissions);
        $this->command->info('All permissions assigned to administrator role.');

        // 5. Assign specific permissions to editor role
        $editorPermissions = Permission::where('name', 'like', '%.view')
            ->orWhere('name', 'like', '%.create')
            ->orWhere('name', 'like', '%.edit')
            ->get();
        $editorRole->syncPermissions($editorPermissions);
        $this->command->info('View, create, and edit permissions assigned to editor role.');

        // 6. Assign view permissions to user role
        $userPermissions = Permission::where('name', 'like', '%.view')->get();
        $userRole->syncPermissions($userPermissions);
        $this->command->info('View permissions assigned to user role.');

        // 7. Create or retrieve admin user
        $adminUser = User::where('email', 'kingslydebruyne17@gmail.com')->first();
        if (!$adminUser) {
            $adminUser = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'kingslydebruyne17@gmail.com',
                'password' => bcrypt('Laurince 17@'), // Change this in production!
            ]);
            $this->command->info('Default admin user created: kingslydebruyne17@gmail.com');
        }

        // 8. Assign admin role to user
        if (!$adminUser->hasRole($adminRole)) {
            $adminUser->assignRole($adminRole);
            $this->command->info('Admin role assigned to user: ' . $adminUser->email);
        }

        // 9. Create example editor and regular users (optional)
        $editorUser = User::where('email', 'editor@example.com')->first();
        if (!$editorUser) {
            $editorUser = User::factory()->create([
                'name' => 'Editor User',
                'email' => 'editor@example.com',
                'password' => bcrypt('password'),
            ]);
            $editorUser->assignRole($editorRole);
            $this->command->info('Editor user created and role assigned.');
        }

        $regularUser = User::where('email', 'user@example.com')->first();
        if (!$regularUser) {
            $regularUser = User::factory()->create([
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'password' => bcrypt('password'),
            ]);
            $regularUser->assignRole($userRole);
            $this->command->info('Regular user created and role assigned.');
        }
    }
}