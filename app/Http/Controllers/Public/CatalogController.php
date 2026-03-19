<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Liste tous les médicaments disponibles
     */
    public function index(Request $request)
    {
        $query = Medicine::with('category')
                         ->where('quantity', '>', 0)
                         ->whereDate('expiry_date', '>=', now());

        // Recherche par nom
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtre par catégorie
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $medicines  = $query->orderBy('name')->paginate(12);
        $categories = Category::orderBy('name')->get();

        return view('public.catalogue', compact('medicines', 'categories'));
    }

    /**
     * Détail d'un médicament
     */
    public function show(Medicine $medicine)
    {
        return view('public.medicine-show', compact('medicine'));
    }
}