<?php

namespace App\Http\Controllers\Elearning;

use App\Http\Controllers\Controller;
use App\Models\Ressource;

class RessourceController extends Controller
{
    // Affiche un PDF de façon sécurisée
public function show($id)
{
    try {
        $ressource = Ressource::findOrFail($id);

        if ($ressource->type !== 'document') {
            return response()->json(['error' => 'Accès refusé'], 403);
        }

        $path = storage_path('app/public/' . $ressource->contenu);

        if (!file_exists($path)) {
            return response()->json(['error' => 'Fichier introuvable'], 404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
        ]);
    } catch (\Exception $e) {
        Log::error('Erreur PDF: ' . $e->getMessage());
        return response()->json(['error' => 'Erreur serveur'], 500);
    }
}
}
