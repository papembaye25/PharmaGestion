<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\CatalogController;
use App\Http\Controllers\Public\OrderController as PublicOrderController;

/*
| ROUTES PUBLIQUES
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalogue', [CatalogController::class, 'index'])->name('catalogue');
Route::get('/catalogue/{medicine}', [CatalogController::class, 'show'])->name('catalogue.show');
Route::post('/commande', [PublicOrderController::class, 'store'])->name('public.order.store');

/*
| ROUTES AUTHENTIFICATION
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
| ROUTES ADMIN
*/
Route::middleware(['auth'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
         ->name('dashboard');

    
    Route::resource('categories', CategoryController::class);

    Route::resource('medicines', MedicineController::class);

    // Stock
    Route::get('/stock', [StockController::class, 'index'])
         ->name('stock.index');

    Route::resource('sales', SaleController::class)
         ->only(['index', 'create', 'store', 'show']);

    Route::resource('orders', OrderController::class)
         ->only(['index', 'show']);

    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
         ->name('orders.updateStatus');
});