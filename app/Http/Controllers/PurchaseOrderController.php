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
        $purchaseOrders = PurchaseOrder::where('division_id', auth()->user()->division_id)->get();

        return view('purchase_orders.index', compact('purchaseOrders'));
    }

    public function pay(PurchaseOrder $purchaseOrder)
    {
        // Pastikan user adalah manager dan berasal dari divisi yang sama dengan PO
        if (auth()->user()->role !== 'manager' || auth()->user()->division_id !== $purchaseOrder->division_id) {
            abort(403, 'Unauthorized');
        }

        // Update status PO menjadi 'paid'
        $purchaseOrder->update(['status' => 'paid']);

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dibayar.');
    }
}
