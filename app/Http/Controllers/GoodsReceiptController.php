<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\Product;
use App\Models\PurchaseOrder;

use Illuminate\Http\Request;

class GoodsReceiptController extends Controller
{
    public function create(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'paid') {
            return redirect()->back()->with('error', 'PO belum dibayar!');
        }
        // Pastikan relasi items() di PurchaseOrder sudah benar
        $items = $purchaseOrder->items ?? collect(); // fallback ke koleksi kosong jika null
        return view('goods_receipts.create', compact('purchaseOrder', 'items'));
    }

    public function store(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'received_at' => 'required|date',
            'receiver' => 'required|string',
            'notes' => 'nullable|string',
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
        ]);

        $goodsReceipt = GoodsReceipt::create([
            'purchase_order_id' => $purchaseOrder->id,
            'received_at' => $request->received_at,
            'receiver' => $request->receiver,
            'notes' => $request->notes,
        ]);

        foreach ($request->quantities as $productId => $qty) {
            GoodsReceiptItem::create([
                'goods_receipt_id' => $goodsReceipt->id,
                'product_id' => $productId,
                'quantity' => $qty,
            ]);
            // Update stok produk
            $product = Product::find($productId);
            if ($product) {
                $product->increment('stock', $qty);
            }
        }

        return redirect()->route('purchase-orders.index')->with('success', 'Barang berhasil diterima dan stok diupdate.');
    }
}
