<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // 6 médicaments les plus récents pour la vitrine
        $medicines = Medicine::with('category')
                             ->where('quantity', '>', 0)
                             ->latest()
                             ->take(6)
                             ->get();

        // Toutes les catégories pour la section navigation
        $categories = Category::withCount('medicines')->get();

        return view('public.home', compact('medicines', 'categories'));
    }
}