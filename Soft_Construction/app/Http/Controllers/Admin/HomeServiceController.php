<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeServiceController extends Controller
{
    public function index()
    {
        $services = HomeService::orderBy('order')->get();
        return view('admin.home-services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.home-services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'feature_title_1' => 'required|string|max:255',
            'feature_description_1' => 'required|string',
            'feature_icon_1' => 'required|string',
            'feature_title_2' => 'required|string|max:255',
            'feature_description_2' => 'required|string',
            'feature_icon_2' => 'required|string',
            'feature_title_3' => 'required|string|max:255',
            'feature_description_3' => 'required|string',
            'feature_icon_3' => 'required|string',
            'button_text' => 'nullable|string|max:255',
            'order' => 'required|integer',
            'is_active' => 'sometimes|boolean'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('home-services', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        HomeService::create($validated);

        return redirect()->route('admin.home-services.index')
                        ->with('success', 'Service created successfully.');
    }

    public function show(HomeService $homeService)
    {
        return view('admin.home-services.show', ['service' => $homeService]);
    }

    public function edit(HomeService $homeService)
    {
        return view('admin.home-services.edit', ['service' => $homeService]);
    }

    public function update(Request $request, HomeService $homeService)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'feature_title_1' => 'required|string|max:255',
            'feature_description_1' => 'required|string',
            'feature_icon_1' => 'required|string',
            'feature_title_2' => 'required|string|max:255',
            'feature_description_2' => 'required|string',
            'feature_icon_2' => 'required|string',
            'feature_title_3' => 'required|string|max:255',
            'feature_description_3' => 'required|string',
            'feature_icon_3' => 'required|string',
            'button_text' => 'nullable|string|max:255',
            'order' => 'required|integer',
            'is_active' => 'sometimes|boolean'
        ]);

        if ($request->hasFile('image')) {
            if ($homeService->image) {
                Storage::disk('public')->delete($homeService->image);
            }
            $validated['image'] = $request->file('image')->store('home-services', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $homeService->update($validated);

        return redirect()->route('admin.home-services.index')
                        ->with('success', 'Service updated successfully.');
    }

    public function destroy(HomeService $homeService)
    {
        if ($homeService->image) {
            Storage::disk('public')->delete($homeService->image);
        }
        
        $homeService->delete();

        return redirect()->route('admin.home-services.index')
                        ->with('success', 'Service deleted successfully.');
    }
}