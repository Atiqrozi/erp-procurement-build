<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\PurchaseOrderItem;

class PurchaseOrderController extends Controller
{
    public function create(PurchaseRequest $purchaseRequest)
    {
        return view('purchase_orders.create', compact('purchaseRequest'));
    }

    public function store(Request $request)
    {
        // Validasi dan proses pembuatan PO
        $purchaseOrder = PurchaseOrder::create([
            'purchase_request_id' => $request->purchase_request_id,
            'user_id' => auth()->id(),
            'division_id' => auth()->user()->division_id,
            'status' => 'pending',
            'total_amount' => $request->total_amount,
        ]);

        // Ambil item dari purchase_request_items
        $purchaseRequest = PurchaseRequest::find($request->purchase_request_id);
        foreach ($purchaseRequest->items as $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price, // pastikan ada kolom price di purchase_request_items
            ]);
        }

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dibuat.');
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
