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
            'status' => 'pending',
            'total_amount' => $request->total_amount,
        ]);

        return redirect()->route('purchase-requests.index')->with('success', 'Purchase order created successfully.');
    }

    public function index()
    {
        $purchaseOrders = PurchaseOrder::all();
        return view('purchase_orders.index', compact('purchaseOrders'));
    }

    public function pay(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->update(['status' => 'paid']);
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dibayar.');
    }
}
