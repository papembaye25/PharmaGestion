<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicineController extends Controller
{
    /**
     * Liste tous les médicaments
     */
    public function index(Request $request)
    {
        $query = Medicine::with('category');

        // Recherche par nom
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtre par catégorie
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $medicines   = $query->orderBy('name')->paginate(10);
        $categories  = Category::orderBy('name')->get();

        return view('admin.medicines.index', compact('medicines', 'categories'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.medicines.create', compact('categories'));
    }

    /**
     * Enregistre le nouveau médicament
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'category_id'     => 'required|exists:categories,id',
            'price'           => 'required|numeric|min:0',
            'quantity'        => 'required|integer|min:0',
            'alert_threshold' => 'required|integer|min:1',
            'expiry_date'     => 'required|date|after:today',
            'description'     => 'nullable|string',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'        => 'Le nom est obligatoire.',
            'category_id.required' => 'Veuillez sélectionner une catégorie.',
            'category_id.exists'   => 'Catégorie invalide.',
            'price.required'       => 'Le prix est obligatoire.',
            'price.numeric'        => 'Le prix doit être un nombre.',
            'quantity.required'    => 'La quantité est obligatoire.',
            'expiry_date.required' => 'La date d\'expiration est obligatoire.',
            'expiry_date.after'    => 'La date d\'expiration doit être dans le futur.',
            'image.image'          => 'Le fichier doit être une image.',
            'image.max'            => 'L\'image ne doit pas dépasser 2 Mo.',
        ]);

        // Gestion de l'image uploadée
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Stocke dans storage/app/public/medicines/
            $imagePath = $request->file('image')->store('medicines', 'public');
        }

        Medicine::create([
            'name'            => $request->name,
            'category_id'     => $request->category_id,
            'price'           => $request->price,
            'quantity'        => $request->quantity,
            'alert_threshold' => $request->alert_threshold,
            'expiry_date'     => $request->expiry_date,
            'description'     => $request->description,
            'image'           => $imagePath,
        ]);

        return redirect()->route('admin.medicines.index')
                         ->with('success', 'Médicament ajouté avec succès.');
    }

    /**
     * Affiche le détail d'un médicament
     */
    public function show(Medicine $medicine)
    {
        return view('admin.medicines.show', compact('medicine'));
    }

    /**
     * Affiche le formulaire de modification
     */
    public function edit(Medicine $medicine)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.medicines.edit', compact('medicine', 'categories'));
    }

    /**
     * Met à jour le médicament
     */
    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'category_id'     => 'required|exists:categories,id',
            'price'           => 'required|numeric|min:0',
            'quantity'        => 'required|integer|min:0',
            'alert_threshold' => 'required|integer|min:1',
            'expiry_date'     => 'required|date',
            'description'     => 'nullable|string',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Gestion image : nouvelle image uploadée ?
        $imagePath = $medicine->image; // On garde l'ancienne par défaut

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($medicine->image) {
                Storage::disk('public')->delete($medicine->image);
            }
            // Stocker la nouvelle
            $imagePath = $request->file('image')->store('medicines', 'public');
        }

        $medicine->update([
            'name'            => $request->name,
            'category_id'     => $request->category_id,
            'price'           => $request->price,
            'quantity'        => $request->quantity,
            'alert_threshold' => $request->alert_threshold,
            'expiry_date'     => $request->expiry_date,
            'description'     => $request->description,
            'image'           => $imagePath,
        ]);

        return redirect()->route('admin.medicines.index')
                         ->with('success', 'Médicament modifié avec succès.');
    }

    /**
     * Supprime le médicament
     */
    public function destroy(Medicine $medicine)
    {
        // Supprimer l'image associée
        if ($medicine->image) {
            Storage::disk('public')->delete($medicine->image);
        }

        $medicine->delete();

        return redirect()->route('admin.medicines.index')
                         ->with('success', 'Médicament supprimé avec succès.');
    }
}