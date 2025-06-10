<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\PurchaseOrderItem;
use App\Models\Balance;

class PurchaseOrderController extends Controller
{
    public function create(PurchaseRequest $purchaseRequest)
    {
        return view('purchase_orders.create', compact('purchaseRequest'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_request_id' => 'required|exists:purchase_requests,id',
            'total_amount' => 'required|numeric|min:0',
        ]);

        // Cegah duplikat PO
        $existingPO = PurchaseOrder::where('purchase_request_id', $request->purchase_request_id)->first();
        if ($existingPO) {
            return redirect()->route('purchase-orders.index')->with('error', 'PO untuk permintaan ini sudah dibuat.');
        }

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

        if ($user->role !== 'manager') {
            abort(403, 'Unauthorized');
        }

        // Update status menjadi paid
        $purchaseOrder->update(['status' => 'paid']);

        $purchaseRequest = $purchaseOrder->purchaseRequest;

        // Update stok produk
        foreach ($purchaseRequest->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->increment('stock', $item->quantity);
            }
        }

        // Tambahkan expense ke balance
        Balance::create([
            'income' => null,
            'expense' => $purchaseOrder->total_amount,
            'information' => 'Pembayaran dari PO-' . $purchaseOrder->id,
            'purchase_orders_id' => $purchaseOrder->id,
            'status' => 'paid',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dibayar dan stok produk diupdate.');
    }
}
