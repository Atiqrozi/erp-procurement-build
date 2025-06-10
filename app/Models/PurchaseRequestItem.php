<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestItem extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplierProduct()
    {
        return $this->belongsTo(SupplierProduct::class, 'supplier_products_id');
    }


    public function request()
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }
    // BENAR
    protected $fillable = ['purchase_request_id', 'product_id', 'quantity', 'supplier_products_id', 'price'];


    use HasFactory;
}
