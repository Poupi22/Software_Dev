<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MatiereController extends Controller
{


    public function index()
    {
        $matieres = Matiere::with('formateur')->latest()->get();
        return view('admin_site.matieres.index', compact('matieres'));
    }

    public function create()
    {
        $formateurs = User::role('Formateur')->get();
        return view('admin_site.matieres.create', compact('formateurs'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255|unique:matieres',
            'code' => 'nullable|string|max:20',
            'credit' => 'nullable|integer|min:1|max:10',
            'description' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id'
        ]);
        Matiere::create($validatedData);
        return redirect()->route('dashboard.matiere.index')->with('success', 'Matière créée.');
    }

    public function edit(Matiere $matiere)
    {
        $formateurs = User::role('Formateur')->get();
        return view('admin_site.matieres.edit', compact('matiere', 'formateurs'));
    }

    public function update(Request $request, Matiere $matiere)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255|unique:matieres,nom,'.$matiere->id,
            'code' => 'nullable|string|max:20',
            'credit' => 'nullable|integer|min:1|max:10',
            'description' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id'
        ]);
        $matiere->update($validatedData);
        return redirect()->route('dashboard.matiere.index')->with('success', 'Matière mise à jour.');
    }

    public function show(Matiere $matiere)
    {
        $matiere->load('chapitres.lecons');

        return view('admin_site.matieres.show', compact('matiere'));
    }

    public function destroy(Matiere $matiere)
    {
        $matiere->delete();
        return redirect()->route('dashboard.matiere.index')->with('success', 'Matière supprimée.');
    }
}
