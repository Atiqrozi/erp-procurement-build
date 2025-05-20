<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function create(PurchaseRequest $purchaseRequest)
    {
        return view('purchase_orders.create', compact('purchaseRequest'));
    }

    public function store(Request $request, PurchaseRequest $purchaseRequest)
    {
        $request->validate([
            'total_amount' => 'required|numeric|min:0',
        ]);

        PurchaseOrder::create([
            'purchase_request_id' => $purchaseRequest->id,
            'user_id' => auth()->id(),
            'division_id' => auth()->user()->division_id, // Ambil divisi dari user yang login
            'status' => 'pending',
            'total_amount' => $request->total_amount,
        ]);

        return redirect()->route('purchase-requests.index')->with('success', 'Purchase Order berhasil dibuat.');
    }

    public function index()
    {
        if (auth()->user()->division_id === 2) {
            $purchaseOrders = PurchaseOrder::all();
        } else {
            // Selain keuangan, hanya lihat PO divisinya sendiri
            $purchaseOrders = PurchaseOrder::where('division_id', auth()->user()->division_id)->get();
        }

        return view('purchase_orders.index', compact('purchaseOrders'));
    }

    public function pay(PurchaseOrder $purchaseOrder)
    {
        // Hanya user keuangan (division_id 2) yang boleh membayar PO
        if (auth()->user()->division_id !== 2 ) {
            abort(403, 'Unauthorized');
        }

        // Update status PO menjadi 'paid'
        $purchaseOrder->update(['status' => 'paid']);

        // Ambil PurchaseRequest terkait
        $purchaseRequest = $purchaseOrder->purchaseRequest; // pastikan ada relasi purchaseRequest di model PurchaseOrder

        // Tambahkan quantity dari setiap item PR ke stok produk
        foreach ($purchaseRequest->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->increment('stock', $item->quantity);
            }
        }

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dibayar dan stok produk diupdate.');
    }
}
