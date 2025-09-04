<?php
namespace App\Http\Controllers;
use App\Models\Qualification;
use Illuminate\Http\Request;

class QualificationController extends Controller
{
    public function index() { 
        $qualifications = Qualification::latest()->get(); 
        return view('admin_site.qualifications.index', compact('qualifications')); 
    }
    
    public function create() { 
        return view('admin_site.qualifications.create'); 
    }
    
    public function store(Request $request) { 
        $v = $request->validate([
            'code' => 'required|unique:qualifications', 
            'nom' => 'required'
        ]); 
        Qualification::create($v); 
        return redirect()->route('dashboard.qualification.index')->with('success', 'Qualification créée.'); 
    }
    
    public function edit(Qualification $qualification) { 
        return view('admin_site.qualifications.edit', compact('qualification')); 
    }
    
    public function update(Request $request, Qualification $qualification) { 
        $v = $request->validate([
            'code' => 'required|unique:qualifications,code,'.$qualification->id, 
            'nom' => 'required'
        ]); 
        $qualification->update($v); 
        return redirect()->route('dashboard.qualification.index')->with('success', 'Qualification mise à jour.'); 
    }
    
    public function destroy(Qualification $qualification) { 
        $qualification->delete(); 
        return redirect()->route('dashboard.qualification.index')->with('success', 'Qualification supprimée.'); 
    }
}