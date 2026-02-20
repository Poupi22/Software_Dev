<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    public function index()
    {
        $abouts = About::all(); // Changed from first() to all()
        return view('admin.about.index', compact('abouts'));
    }

    public function create()
    {
        return view('admin.about.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'description1' => 'required|string',
            'description2' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'experience_years' => 'required|string|max:10',
            'experience_text' => 'required|string|max:255',
            'features' => 'required|array',
            'features.*.icon' => 'required|string',
            'features.*.title' => 'required|string',
            'features.*.description' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('about', 'public');
        }

        About::create($validated);

        return redirect()->route('admin.abouts.index')->with('success', 'About section created successfully.');
    }

    public function show(About $about)
    {
        return view('admin.about.show', compact('about'));
    }

    public function edit(About $about)
    {
        return view('admin.about.edit', compact('about'));
    }

    public function update(Request $request, About $about)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'description1' => 'required|string',
            'description2' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'experience_years' => 'required|string|max:10',
            'experience_text' => 'required|string|max:255',
            'features' => 'required|array',
            'features.*.icon' => 'required|string',
            'features.*.title' => 'required|string',
            'features.*.description' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($about->image) {
                Storage::disk('public')->delete($about->image);
            }
            $validated['image'] = $request->file('image')->store('about', 'public');
        }

        $about->update($validated);

        return redirect()->route('admin.abouts.index')->with('success', 'About section updated successfully.');
    }

    public function destroy(About $about)
    {
        if ($about->image) {
            Storage::disk('public')->delete($about->image);
        }
        $about->delete();
        return redirect()->route('admin.abouts.index')->with('success', 'About section deleted successfully.');
    }
}