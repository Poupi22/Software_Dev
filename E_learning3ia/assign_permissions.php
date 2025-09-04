<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// Créer les permissions
$permissions = [
    'view notes',
    'create notes', 
    'update notes',
    'delete notes',
    'view assiduite',
    'create assiduite',
    'update assiduite', 
    'delete assiduite'
];

foreach ($permissions as $permission) {
    Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
}

echo "✓ Permissions créées\n";

// Assigner au rôle Administrateur
$admin = Role::where('name', 'Administrateur')->first();

if ($admin) {
    $admin->givePermissionTo($permissions);
    echo "✓ Permissions assignées au rôle Administrateur\n";
} else {
    echo "✗ Rôle Administrateur non trouvé\n";
}

echo "\n";
