<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::orderBy('order')->get();
        return view('admin.trainings.index', compact('trainings'));
    }

    public function create()
    {
        return view('admin.trainings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean',
            'order' => 'integer'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('trainings', 'public');
        }

        $validated['slug'] = Str::slug($request->title);
        $validated['is_active'] = $request->has('is_active');

        Training::create($validated);

        return redirect()->route('admin.trainings.index')->with('success', 'Training program created successfully.');
    }

    public function edit(Training $training)
    {
        return view('admin.trainings.edit', compact('training'));
    }

    public function update(Request $request, Training $training)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean',
            'order' => 'integer'
        ]);

        if ($request->hasFile('image')) {
            if ($training->image) {
                Storage::disk('public')->delete($training->image);
            }
            $validated['image'] = $request->file('image')->store('trainings', 'public');
        }

        $validated['slug'] = Str::slug($request->title);
        $validated['is_active'] = $request->has('is_active');

        $training->update($validated);

        return redirect()->route('admin.trainings.index')->with('success', 'Training program updated successfully.');
    }
    public function show(Training $training)
{
    return view('admin.trainings.show', compact('training'));
}

    public function destroy(Training $training)
    {
        if ($training->image) {
            Storage::disk('public')->delete($training->image);
        }
        $training->delete();
        return redirect()->route('admin.trainings.index')->with('success', 'Training program deleted successfully.');
    }
}