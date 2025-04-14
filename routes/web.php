<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//  Landing page untuk publik
Route::get('/', function () {
    return view('index');
})->name('home');

// Auth routes dari Laravel Breeze (login, register, dll.)
require __DIR__.'/auth.php';

// Route yang hanya bisa diakses setelah login
Route::middleware(['auth'])->group(function () {

    // Dashboard default Breeze
    Route::get('/dashboard', function () {
        return redirect('/');
    })->name('dashboard');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Product
    Route::resource('/products', ProductController::class);

    // Supplier
    Route::resource('/suppliers', SupplierController::class);

    // Purchase Request (PR)
    Route::resource('/purchase-requests', PurchaseRequestController::class);

    Route::get('/purchase-requests/{purchaseRequest}/purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
    Route::post('/purchase-requests/{purchaseRequest}/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');

    // Route untuk Purchase Orders
    Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
    Route::put('/purchase-orders/{purchaseOrder}/pay', [PurchaseOrderController::class, 'pay'])->name('purchase-orders.pay');

    // Approve / Reject hanya untuk admin
    Route::middleware('role:admin')->group(function () {
        Route::put('/purchase-requests/{purchase_request}/approve', [PurchaseRequestController::class, 'approve'])->name('purchase-requests.approve');
        Route::put('/purchase-requests/{purchase_request}/reject', [PurchaseRequestController::class, 'reject'])->name('purchase-requests.reject');
    });
});
