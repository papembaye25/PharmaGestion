<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Liste toutes les catégories
     */
    public function index()
    {
        // withCount: compte les médicaments de chaque catégorie
        $categories = Category::withCount('medicines')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Enregistre la nouvelle catégorie en BDD
     */
    public function store(Request $request)
    {
        // Étape 1 : Valider les données du formulaire
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
        ], [
            // Messages d'erreur en français
            'name.required' => 'Le nom de la catégorie est obligatoire.',
            'name.unique'   => 'Cette catégorie existe déjà.',
            'name.max'      => 'Le nom ne peut pas dépasser 255 caractères.',
        ]);

        // Étape 2 : Créer la catégorie
        Category::create($request->only('name', 'description'));

        // Étape 3 : Rediriger avec message de succès
        return redirect()->route('admin.categories.index')
                         ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Affiche le formulaire de modification
     */
    public function edit(Category $category)
    {
        // Laravel injecte automatiquement la catégorie grâce au Route Model Binding
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Met à jour la catégorie en BDD
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            // ignore=... exclut la catégorie actuelle du test d'unicité
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Le nom de la catégorie est obligatoire.',
            'name.unique'   => 'Cette catégorie existe déjà.',
        ]);

        $category->update($request->only('name', 'description'));

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Catégorie modifiée avec succès.');
    }

    /**
     * Supprime la catégorie
     */
    public function destroy(Category $category)
    {
        // Vérifier si la catégorie contient des médicaments
        if ($category->medicines()->count() > 0) {
            return redirect()->route('admin.categories.index')
                             ->with('error', 'Impossible de supprimer : cette catégorie contient des médicaments.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Catégorie supprimée avec succès.');
    }
}