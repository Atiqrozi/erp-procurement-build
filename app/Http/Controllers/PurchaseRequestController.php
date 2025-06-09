<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PurchaseRequestItem;
use Illuminate\Support\Facades\Auth;

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
        return view('purchase_requests.create', compact('products'));
    }

    /**
     * Menyimpan permintaan pembelian baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'quantities' => 'required|array',
        ]);

        $pr = PurchaseRequest::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        foreach ($request->product_ids as $i => $productId) {
            PurchaseRequestItem::create([
                'purchase_request_id' => $pr->id,
                'product_id' => $productId,
                'quantity' => $request->quantities[$i],
            ]);
        }

        return redirect()->route('purchase-requests.index')->with('success', 'Permintaan berhasil dibuat.');
    }

    /**
     * Menyetujui permintaan pembelian.
     */
    public function approve(PurchaseRequest $purchaseRequest)
    {
        if (Auth::user()->role !== 'manager') {
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
        if (Auth::user()->role !== 'manager') {
            abort(403, 'Unauthorized');
        }

        $purchaseRequest->update(['status' => 'rejected']);

        return redirect()->route('purchase-requests.index')->with('success', 'Permintaan ditolak.');
    }
}
