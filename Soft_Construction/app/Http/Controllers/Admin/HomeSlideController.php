<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeSlideController extends Controller
{
    public function index()
    {
        $slides = HomeSlide::orderBy('order')->get();
        return view('admin.home_slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.home_slides.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image1' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        // Store images
        $validated['image1'] = $request->file('image1')->store('home_slides', 'public');
        if ($request->hasFile('image2')) {
            $validated['image2'] = $request->file('image2')->store('home_slides', 'public');
        }
        if ($request->hasFile('image3')) {
            $validated['image3'] = $request->file('image3')->store('home_slides', 'public');
        }

        HomeSlide::create($validated);

        return redirect()->route('admin.home_slides.index')->with('success', 'Slide created successfully!');
    }

    public function edit(HomeSlide $home_slide)
    {
        return view('admin.home_slides.edit', compact('home_slide'));
    }

    public function update(Request $request, HomeSlide $home_slide)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        // Update images if new ones are provided
        if ($request->hasFile('image1')) {
            Storage::disk('public')->delete($home_slide->image1);
            $validated['image1'] = $request->file('image1')->store('home_slides', 'public');
        }
        if ($request->hasFile('image2')) {
            if ($home_slide->image2) {
                Storage::disk('public')->delete($home_slide->image2);
            }
            $validated['image2'] = $request->file('image2')->store('home_slides', 'public');
        }
        if ($request->hasFile('image3')) {
            if ($home_slide->image3) {
                Storage::disk('public')->delete($home_slide->image3);
            }
            $validated['image3'] = $request->file('image3')->store('home_slides', 'public');
        }

        $home_slide->update($validated);

        return redirect()->route('admin.home_slides.index')->with('success', 'Slide updated successfully!');
    }
    public function show($id)
{
    $slide = HomeSlide::findOrFail($id);
    return view('admin.home_slides.show', compact('slide'));
}

    public function destroy(HomeSlide $home_slide)
    {
        // Delete all images
        Storage::disk('public')->delete($home_slide->image1);
        if ($home_slide->image2) {
            Storage::disk('public')->delete($home_slide->image2);
        }
        if ($home_slide->image3) {
            Storage::disk('public')->delete($home_slide->image3);
        }
        
        $home_slide->delete();

        return redirect()->route('admin.home_slides.index')->with('success', 'Slide deleted successfully!');
    }
}