<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Carbon\Carbon;

class StockController extends Controller
{
    public function index()
    {
        // Tous les médicaments avec leur catégorie
        $medicines = Medicine::with('category')
                             ->orderBy('name')
                             ->get();

        // Médicaments stock faible
        $low_stock = Medicine::with('category')
                             ->whereColumn('quantity', '<=', 'alert_threshold')
                             ->get();

        // Médicaments expirés
        $expired = Medicine::with('category')
                           ->whereDate('expiry_date', '<', Carbon::today())
                           ->get();

        // Médicaments expirant dans 30 jours
        $expiring_soon = Medicine::with('category')
                                 ->whereDate('expiry_date', '>=', Carbon::today())
                                 ->whereDate('expiry_date', '<=', Carbon::today()->addDays(30))
                                 ->get();

        return view('admin.stock.index', compact(
            'medicines', 'low_stock', 'expired', 'expiring_soon'
        ));
    }
}