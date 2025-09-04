<?php

namespace App\Http\Controllers\Elearning;
namespace App\Http\Controllers;

use App\Models\Lecon;
use App\Models\Ressource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RessourceController extends Controller
{
    public function create(Request $request)
    {
        $request->validate(['lecon_id' => 'required|exists:lecons,id']);
        $lecon = Lecon::find($request->lecon_id);
        return view('admin_site.ressources.create', compact('lecon'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'lecon_id' => 'required|exists:lecons,id',
            'type' => 'required|in:texte,video,document,quiz',
            'contenu' => 'nullable|string',
            'fichier' => 'nullable|file|mimes:pdf,doc,docx,zip|max:10240',
        ]);

        $lecon = Lecon::find($validated['lecon_id']);
        $validated['ordre'] = ($lecon->ressources()->max('ordre') ?? 0) + 1;

        if ($request->hasFile('fichier')) {
            $validated['contenu'] = $request->file('fichier')->store('ressources_documents', 'public');
        }

        Ressource::create($validated);

        return redirect()->route('dashboard.lecon.edit', $lecon->id)
                         ->with('success', 'Ressource ajoutée.');
    }

    public function destroy(Ressource $ressource)
    {
        $leconId = $ressource->lecon_id;
        if ($ressource->type === 'document' && $ressource->contenu) {
            Storage::disk('public')->delete($ressource->contenu);
        }
        $ressource->delete();
        return redirect()->route('dashboard.lecon.edit', $leconId)
                         ->with('success', 'Ressource supprimée.');
    }
}
