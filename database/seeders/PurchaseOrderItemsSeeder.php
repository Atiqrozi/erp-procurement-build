<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;

class PurchaseOrderItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua purchase orders beserta purchase request dan itemnya
        $purchaseOrders = PurchaseOrder::with('purchaseRequest.items')->get();

        foreach ($purchaseOrders as $po) {
            $purchaseRequest = $po->purchaseRequest;
            if ($purchaseRequest && $purchaseRequest->items) {
                foreach ($purchaseRequest->items as $item) {
                    // Cek jika sudah ada, skip agar tidak double
                    $exists = PurchaseOrderItem::where([
                        'purchase_order_id' => $po->id,
                        'product_id' => $item->product_id,
                    ])->exists();

                    if (!$exists) {
                        PurchaseOrderItem::create([
                            'purchase_order_id' => $po->id,
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                            'price' => $item->price ?? 0,
                        ]);
                    }
                }
            }
        }
    }
}
