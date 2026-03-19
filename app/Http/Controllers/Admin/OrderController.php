<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Liste toutes les commandes
     */
    public function index(Request $request)
    {
        $query = Order::with('items.medicine');

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Plus récentes en premier
        $orders = $query->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Détail d'une commande
     */
    public function show(Order $order)
    {
        // Charger les relations nécessaires
        $order->load('items.medicine');

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Changer le statut d'une commande
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:en_attente,validee,livree',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.show', $order)
                         ->with('success', 'Statut mis à jour avec succès.');
    }
}