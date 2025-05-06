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
        $this->middleware('auth'); // Pastikan user sudah login
    }

    /**
     * Menampilkan daftar permintaan pembelian berdasarkan divisi user yang login.
     */
    public function index()
    {
        $requests = PurchaseRequest::with('user', 'items.product') // Eager load relasi untuk menghindari query tambahan
            ->where('division_id', Auth::user()->division_id) // Filter berdasarkan divisi user
            ->get();

        return view('purchase_requests.index', compact('requests'));
    }

    /**
     * Menampilkan form untuk membuat permintaan pembelian baru.
     */
    public function create()
    {
        $products = Product::where('division_id', auth()->user()->division_id)->get(); // Ambil semua produk
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

        // Buat permintaan pembelian baru
        $pr = PurchaseRequest::create([
            'user_id' => Auth::id(),
            'division_id' => Auth::user()->division_id, // Ambil divisi dari user yang login
            'status' => 'pending',
        ]);

        // Simpan item permintaan pembelian
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
        // Validasi: User harus manager dan berada di divisi yang sama
        if (Auth::user()->role !== 'manager' || Auth::user()->division_id !== $purchaseRequest->division_id) {
            abort(403, 'Unauthorized');
        }

        // Update status menjadi 'approved'
        $purchaseRequest->update(['status' => 'approved']);

        return redirect()->route('purchase-requests.index')->with('success', 'Permintaan disetujui.');
    }

    /**
     * Menolak permintaan pembelian.
     */
    public function reject(PurchaseRequest $purchaseRequest)
    {
        // Validasi: User harus manager dan berada di divisi yang sama
        if (Auth::user()->role !== 'manager' || Auth::user()->division_id !== $purchaseRequest->division_id) {
            abort(403, 'Unauthorized');
        }

        // Update status menjadi 'rejected'
        $purchaseRequest->update(['status' => 'rejected']);

        return redirect()->route('purchase-requests.index')->with('success', 'Permintaan ditolak.');
    }
}
