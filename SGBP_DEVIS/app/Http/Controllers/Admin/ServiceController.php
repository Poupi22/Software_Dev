<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::query()
            ->when($request->search, fn($q, $s) => $q->where('nom', 'like', "%{$s}%"))
            ->orderBy('ordre')
            ->paginate(15)
            ->withQueryString();

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
            'description' => 'required|string',
            'description_courte' => 'nullable|string|max:500',
            'ordre' => 'nullable|integer|min:0',
            'actif' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('services', 'public');
        }

        $validated['actif'] = $request->boolean('actif', true);
        unset($validated['image']);

        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service créé avec succès !');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
            'description' => 'required|string',
            'description_courte' => 'nullable|string|max:500',
            'ordre' => 'nullable|integer|min:0',
            'actif' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($service->image_path) {
                Storage::disk('public')->delete($service->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('services', 'public');
        }

        $validated['actif'] = $request->boolean('actif', true);
        unset($validated['image']);

        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service modifié avec succès !');
    }

    public function destroy(Service $service)
    {
        if ($service->image_path) {
            Storage::disk('public')->delete($service->image_path);
        }

        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service supprimé avec succès !');
    }
}
