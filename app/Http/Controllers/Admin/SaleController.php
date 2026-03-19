<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Liste toutes les ventes
     */
    public function index()
    {
        $sales = Sale::with('user')->latest()->paginate(15);

        return view('admin.sales.index', compact('sales'));
    }

    /**
     * Affiche le formulaire de nouvelle vente
     */
    public function create()
    {
        // On récupère uniquement les médicaments en stock
        $medicines = Medicine::where('quantity', '>', 0)
                             ->orderBy('name')
                             ->get();

        return view('admin.sales.create', compact('medicines'));
    }

    /**
     * Enregistre la vente
     */
    public function store(Request $request)
    {
        // Étape 1 : Validation
        $request->validate([
            'medicines'   => 'required|array|min:1',
            'medicines.*' => 'exists:medicines,id',
            'quantities'  => 'required|array|min:1',
            'quantities.*'=> 'integer|min:1',
        ], [
            'medicines.required' => 'Sélectionnez au moins un médicament.',
            'medicines.min'      => 'Sélectionnez au moins un médicament.',
        ]);

        // Étape 2 : Vérifier le stock de chaque médicament
        foreach ($request->medicines as $index => $medicineId) {
            $medicine = Medicine::find($medicineId);
            $quantity = $request->quantities[$index];

            if ($medicine->quantity < $quantity) {
                return back()
                    ->withInput()
                    ->with('error', "Stock insuffisant pour {$medicine->name}. 
                                    Stock disponible : {$medicine->quantity}");
            }
        }

        // Étape 3 : Transaction DB
        // Si une erreur survient au milieu, tout est annulé en fait c'est la loi de tout ou rien
        DB::transaction(function () use ($request) {

            // Calculer le total
            $total = 0;
            $items = [];

            foreach ($request->medicines as $index => $medicineId) {
                $medicine = Medicine::find($medicineId);
                $quantity = $request->quantities[$index];
                $unitPrice = $medicine->price;
                $subtotal  = $unitPrice * $quantity;
                $total    += $subtotal;

                $items[] = [
                    'medicine_id' => $medicineId,
                    'quantity'    => $quantity,
                    'unit_price'  => $unitPrice,
                ];
            }

            // Créer la vente
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'total'   => $total,
                'notes'   => request('notes'),
            ]);

            // Créer les lignes + décrémenter le stock
            foreach ($items as $item) {
                $sale->items()->create($item);

                // Décrémenter le stock
                Medicine::find($item['medicine_id'])
                        ->decrement('quantity', $item['quantity']);
            }
        });

        return redirect()->route('admin.sales.index')
                         ->with('success', 'Vente enregistrée avec succès.');
    }

    /**
     * Détail d'une vente
     */
    public function show(Sale $sale)
    {
        $sale->load('items.medicine', 'user');
        return view('admin.sales.show', compact('sale'));
    }
}