<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Enregistre la commande du client
     */
    public function store(Request $request)
    {
        // Étape 1 : Validation des données client
        $request->validate([
            'client_name'    => 'required|string|max:255',
            'client_phone'   => 'required|string|max:20',
            'client_address' => 'required|string',
            'payment_method' => 'required|in:livraison,whatsapp',
            'medicines'      => 'required|array|min:1',
            'medicines.*'    => 'exists:medicines,id',
            'quantities'     => 'required|array|min:1',
            'quantities.*'   => 'integer|min:1',
        ], [
            'client_name.required'    => 'Votre nom est obligatoire.',
            'client_phone.required'   => 'Votre téléphone est obligatoire.',
            'client_address.required' => 'Votre adresse est obligatoire.',
            'medicines.required'      => 'Votre panier est vide.',
        ]);

        // Étape 2 : Vérifier stock disponible
        foreach ($request->medicines as $index => $medicineId) {
            $medicine = Medicine::find($medicineId);
            $quantity = $request->quantities[$index];

            if (!$medicine || $medicine->quantity < $quantity) {
                return back()
                    ->withInput()
                    ->with('error', "Stock insuffisant pour {$medicine->name}.");
            }
        }

        // Étape 3 : Enregistrer la commande en transaction
        DB::transaction(function () use ($request) {

            $total = 0;
            $items = [];

            foreach ($request->medicines as $index => $medicineId) {
                $medicine  = Medicine::find($medicineId);
                $quantity  = $request->quantities[$index];
                $unitPrice = $medicine->price;
                $total    += $unitPrice * $quantity;

                $items[] = [
                    'medicine_id' => $medicineId,
                    'quantity'    => $quantity,
                    'unit_price'  => $unitPrice,
                ];
            }

            // Créer la commande
            $order = Order::create([
                'client_name'    => $request->client_name,
                'client_phone'   => $request->client_phone,
                'client_address' => $request->client_address,
                'payment_method' => $request->payment_method,
                'status'         => 'en_attente',
                'total'          => $total,
            ]);

            // Créer les lignes de commande
            foreach ($items as $item) {
                $order->items()->create($item);
            }
        });

        // Étape 4 : Rediriger avec succès
        return redirect()->route('catalogue')
                         ->with('success', 'Commande envoyée avec succès ! 
                                            Nous vous contacterons bientôt.');
    }
}