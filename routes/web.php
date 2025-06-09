<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\GoodsReceiptController;
use App\Http\Controllers\SupplierRatingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//  Landing page untuk publik
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DivisionController::class, 'index'])->name('divisions.index');
});


// Auth routes dari Laravel Breeze (login, register, dll.)
require __DIR__ . '/auth.php';

// Route yang hanya bisa diakses setelah login
Route::middleware(['auth'])->group(function () {

    // Dashboard default Breeze
    Route::get('/dashboard', function () {
        return redirect('/');
    })->name('dashboard');
    Route::get('/divisions/{division}', [DivisionController::class, 'show'])->middleware('auth')->name('divisions.show');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Product
    Route::resource('/products', ProductController::class);

    // Supplier
    Route::resource('/suppliers', SupplierController::class);
    Route::put('/suppliers/{supplier}/status', [SupplierController::class, 'changeStatus'])->name('suppliers.changeStatus');



    // Supplier Ratings
    Route::resource('/supplier_rating', SupplierRatingController::class)
        ->only(['index', 'create', 'store', 'edit', 'update']);


    // Purchase Request (PR)
    Route::resource('/purchase-requests', PurchaseRequestController::class);

    Route::get('/purchase-requests/{purchaseRequest}/purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
    Route::post('/purchase-requests/{purchaseRequest}/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');

    // Route untuk Purchase Orders
    Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
    Route::put('/purchase-orders/{purchaseOrder}/pay', [PurchaseOrderController::class, 'pay'])
        ->name('purchase-orders.pay');

    Route::get('/purchase-orders/{purchaseOrder}/goods-receipts/create', [GoodsReceiptController::class, 'create'])->name('goods-receipts.create');
    Route::post('/purchase-orders/{purchaseOrder}/goods-receipts', [GoodsReceiptController::class, 'store'])->name('goods-receipts.store');
});

Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
    Route::put('/purchase-requests/{purchaseRequest}/approve', [PurchaseRequestController::class, 'approve'])->name('purchase-requests.approve');
    Route::put('/purchase-requests/{purchaseRequest}/reject', [PurchaseRequestController::class, 'reject'])->name('purchase-requests.reject');
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{user}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{user}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{user}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::get('/blacklist', [SupplierController::class, 'blacklist'])->name('blacklist.index');
});



Route::get('register', [RegisteredUserController::class, 'showRegistrationForm'])->name('register');
