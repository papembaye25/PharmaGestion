<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Category;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_medicines'  => Medicine::count(),
            'total_categories' => Category::count(),
            'total_orders'     => Order::count(),
            'pending_orders'   => Order::where('status', 'en_attente')->count(),
            'today_sales'      => Sale::whereDate('created_at', Carbon::today())->count(),
        ];

        // Médicaments avec stock faible
        $low_stock = Medicine::whereColumn('quantity', '<=', 'alert_threshold')->get();

        // Médicaments expirés
        $expired = Medicine::whereDate('expiry_date', '<', Carbon::today())->get();

        return view('admin.dashboard', compact('stats', 'low_stock', 'expired'));
    }
}