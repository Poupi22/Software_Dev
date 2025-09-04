<?php
namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use App\Helpers\PermissionHelper;

class GeneratePermissionsAfterMigrate
{
    public function handle(): void
    {
        try {
            PermissionHelper::generateCrudPermissionsForAllTables();
            Log::info('✅ Permissions CRUD générées automatiquement après migration.');
        } catch (\Exception $e) {
            Log::error('❌ Erreur génération permissions CRUD : ' . $e->getMessage());
        }
    }
}
