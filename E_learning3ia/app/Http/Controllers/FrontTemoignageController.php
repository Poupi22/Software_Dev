<?php

namespace App\Http\Controllers;

use App\Models\Temoignage;

class FrontTemoignageController extends FrontBaseController
{
    public function index()
    {
        $search = request('search');
        
        $temoignages = Temoignage::query()
            ->when($search, function($query) use ($search) {
                $this->applySearch($query, $search, ['nom', 'profession', 'contenu']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->itemsPerPage);
            
        return view('acceuil.index', compact('temoignages'));
    }
}