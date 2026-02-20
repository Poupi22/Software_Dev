<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('order')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    

    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'short_description' => 'required|string',
        'long_description' => 'nullable|string',
        'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'order' => 'nullable|integer',
        'active' => 'boolean',
    ]);

    if ($request->hasFile('icon')) {
        $validated['icon'] = $request->file('icon')->store('services/icons', 'public');
    }

    Service::create($validated);

    return redirect()->route('admin.services.index')
        ->with('success', 'Service created successfully.');
}

public function update(Request $request, Service $service)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'short_description' => 'required|string',
        'long_description' => 'nullable|string',
        'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'order' => 'nullable|integer',
        'active' => 'boolean',
    ]);

    if ($request->hasFile('icon')) {
        // Delete old icon if it exists
        if ($service->icon) {
            Storage::disk('public')->delete($service->icon);
        }
        $validated['icon'] = $request->file('icon')->store('services/icons', 'public');
    }

    $service->update($validated);

    return redirect()->route('admin.services.index')
        ->with('success', 'Service updated successfully.');
}

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }
}