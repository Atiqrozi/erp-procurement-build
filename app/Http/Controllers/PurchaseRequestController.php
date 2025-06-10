<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PurchaseRequestItem;
use Illuminate\Support\Facades\Auth;
use App\Models\SupplierProduct;

class PurchaseRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan daftar permintaan pembelian.
     */
    public function index()
    {
        $requests = PurchaseRequest::with('user', 'items.product')->get();
        return view('purchase_requests.index', compact('requests'));
    }

    /**
     * Menampilkan form untuk membuat permintaan pembelian baru.
     */
    public function create()
    {
        $products = Product::all();
        $supplierProducts = SupplierProduct::with('supplier', 'product')->get();
        return view('purchase_requests.create', compact('products', 'supplierProducts'));
    }

    /**
     * Menyimpan permintaan pembelian baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'quantities' => 'required|array',
            'supplier_products_ids' => 'required|array',
        ]);


        $pr = PurchaseRequest::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        foreach ($request->product_ids as $index => $productId) {
            $supplierProductId = $request->supplier_products_ids[$index];
            $quantity = $request->quantities[$index];

            $supplierProduct = SupplierProduct::findOrFail($supplierProductId);

            $pr->items()->create([
                'product_id' => $productId,
                'supplier_products_id' => $supplierProductId,
                'quantity' => $quantity,
                'price' => $supplierProduct->price, // << ini ditambahkan
            ]);
        }


        return redirect()->route('purchase-requests.index')->with('success', 'Permintaan berhasil dibuat.');
    }

    /**
     * Menyetujui permintaan pembelian.
     */
    public function approve(PurchaseRequest $purchaseRequest)
    {
        $user = Auth::user();

        // Hanya tolak akses jika:
        // - Bukan manager DAN
        // - Bukan dari divisi 5
        if (!($user->role === 'manager' || $user->division_id == 5)) {
            abort(403, 'Unauthorized');
        }

        $purchaseRequest->update(['status' => 'approved']);

        return redirect()->route('purchase-requests.index')->with('success', 'Permintaan disetujui.');
    }


    /**
     * Menolak permintaan pembelian.
     */
    public function reject(PurchaseRequest $purchaseRequest)
    {
        $user = Auth::user();

        // Hanya tolak akses jika:
        // - Bukan manager DAN
        // - Bukan dari divisi 5
        if (!($user->role === 'manager' || $user->division_id == 5)) {
            abort(403, 'Unauthorized');
        }

        $purchaseRequest->update(['status' => 'rejected']);

        return redirect()->route('purchase-requests.index')->with('success', 'Permintaan ditolak.');
    }
}
