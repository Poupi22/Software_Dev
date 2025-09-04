<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    public function index()
{
    $forums = Forum::with(['formation', 'creator'])->latest()->paginate(10); // Add pagination
    return view('admin.forums.index', compact('forums'));
}

    public function create()
{
    $formations = Formation::select('id', 'nom')->get();
    return view('admin.forums.create', compact('formations'));
}

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:forums',
        'description' => 'nullable|string',
        'formation_id' => 'required|exists:formations,id'
    ]);

    // Retirez user_id des données créées
    Forum::create([
        'name' => $request->name,
        'slug' => Str::slug($request->name),
        'description' => $request->description,
        'formation_id' => $request->formation_id

    ]);

    return redirect()->route('dashboard1.forum.index')
           ->with('success', 'Forum créé avec succès');
}

    public function show(Forum $forum)
    {
        return view('admin.forums.show', compact('forum'));
    }

    public function edit(Forum $forum)
    {
        $formations = Formation::all();
        return view('admin.forums.edit', compact('forum', 'formations'));
    }

    public function update(Request $request, Forum $forum)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:forums,name,'.$forum->id,
            'description' => 'nullable|string',
            'formation_id' => 'required|exists:formations,id'
        ]);

        $forum->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'formation_id' => $request->formation_id
        ]);

        return redirect()->route('dashboard1.forum.index')
               ->with('success', 'Forum mis à jour avec succès');
    }

    public function destroy(Forum $forum)
    {
        $forum->delete();
        return redirect()->route('admin.forum.index')
               ->with('success', 'Forum supprimé avec succès');
    }
}
