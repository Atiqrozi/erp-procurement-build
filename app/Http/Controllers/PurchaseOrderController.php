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
        $purchaseOrder = PurchaseOrder::create([
            'purchase_request_id' => $request->purchase_request_id,
            'user_id' => auth()->id(),
            'status' => 'pending',
            'total_amount' => $request->total_amount,
        ]);

        $purchaseRequest = PurchaseRequest::find($request->purchase_request_id);
        foreach ($purchaseRequest->items as $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dibuat.');
    }

    public function index()
    {
        $purchaseOrders = PurchaseOrder::all();
        return view('purchase_orders.index', compact('purchaseOrders'));
    }

    public function pay(PurchaseOrder $purchaseOrder)
    {
        $user = auth()->user();

        // Validasi sederhana, ubah jika pakai role
        if ($user->role !== 'keuangan') {
            abort(403, 'Unauthorized');
        }

        $purchaseOrder->update(['status' => 'paid']);

        $purchaseRequest = $purchaseOrder->purchaseRequest;

        foreach ($purchaseRequest->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->increment('stock', $item->quantity);
            }
        }

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dibayar dan stok produk diupdate.');
    }
}
