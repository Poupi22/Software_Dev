<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $categories = ['Éducation', 'Commercial', 'Résidentiel', 'Public', 'Tourisme'];
        $regions = ['Ouest', 'Littoral', 'Centre', 'Nord', 'Sud', 'Est', 'Nord-Ouest', 'Sud-Ouest', 'Adamaoua', 'Extrême-Nord'];
        return view('admin.projects.create', compact('categories', 'regions'));
    }

public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'sometimes|boolean',
        ], [
            'image.required' => 'The project image is required',
            'image.image' => 'The file must be an image',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif',
            'image.max' => 'The image may not be greater than 2MB',
        ]);

        $imagePath = $request->file('image')->store('projects', 'public');

        $project = Project::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'],
            'location' => $validated['location'],
            'region' => $validated['region'],
            'image' => $imagePath,
            'is_featured' => $request->has('is_featured'),
        ]);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project created successfully.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput();
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error creating project: ' . $e->getMessage())
            ->withInput();
    }
}

    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $categories = ['Éducation', 'Commercial', 'Résidentiel', 'Public', 'Tourisme'];
        $regions = ['Ouest', 'Littoral', 'Centre', 'Nord', 'Sud', 'Est', 'Nord-Ouest', 'Sud-Ouest', 'Adamaoua', 'Extrême-Nord'];
        return view('admin.projects.edit', compact('project', 'categories', 'regions'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'location' => $request->location,
            'region' => $request->region,
            'is_featured' => $request->has('is_featured'),
        ];

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($project->image);
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        Storage::disk('public')->delete($project->image);
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }
};