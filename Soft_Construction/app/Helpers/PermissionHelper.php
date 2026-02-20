<?php

namespace App\Helpers;

use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionHelper
{
    public static function generateCrudPermissionsForAllTables()
    {
        // Get all tables from the database
        $tables = DB::select('SHOW TABLES');
        
        // Define CRUD operations
        $operations = ['view', 'create', 'edit', 'delete'];
        
        foreach ($tables as $table) {
            $tableName = reset($table);
            
            // Skip migration and other system tables if needed
            if (strpos($tableName, 'migration') !== false || 
                strpos($tableName, 'password_resets') !== false ||
                strpos($tableName, 'failed_jobs') !== false ||
                strpos($tableName, 'permission') !== false) {
                continue;
            }
            
            // Generate permissions for each CRUD operation
            foreach ($operations as $operation) {
                $permissionName = $tableName . '.' . $operation;
                
                // Create permission if it doesn't exist
                Permission::firstOrCreate(['name' => $permissionName]);
            }
        }
        
        // Add any additional custom permissions that might not be tied to tables
        $customPermissions = [
            'settings.manage',
            'reports.view',
            'backup.manage',
            // Add more as needed
        ];
        
        foreach ($customPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}