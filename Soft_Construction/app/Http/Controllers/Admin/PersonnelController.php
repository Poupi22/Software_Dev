<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PersonnelController extends Controller
{
    public function index()
    {
        $personnels = Personnel::ordered()->get();
        return view('admin.personnels.index', compact('personnels'));
    }

    public function create()
    {
        return view('admin.personnels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('personnels', 'public');
        }

        Personnel::create($validated);

        return redirect()->route('admin.personnels.index')
            ->with('success', 'Personnel created successfully.');
    }

    public function show(Personnel $personnel)
    {
        return view('admin.personnels.show', compact('personnel'));
    }

    public function edit(Personnel $personnel)
    {
        return view('admin.personnels.edit', compact('personnel'));
    }

    public function update(Request $request, Personnel $personnel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($personnel->image_path) {
                Storage::disk('public')->delete($personnel->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('personnels', 'public');
        }

        $personnel->update($validated);

        return redirect()->route('admin.personnels.index')
            ->with('success', 'Personnel updated successfully.');
    }

    public function destroy(Personnel $personnel)
    {
        if ($personnel->image_path) {
            Storage::disk('public')->delete($personnel->image_path);
        }

        $personnel->delete();

        return redirect()->route('admin.personnels.index')
            ->with('success', 'Personnel deleted successfully.');
    }
}