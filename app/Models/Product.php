<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'sku', 'description', 'unit', 'price'];
    use HasFactory;
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_products')
                    ->withPivot('price')
                    ->withTimestamps();
    }
    public function purchaseRequestItems()
    {
        return $this->hasMany(PurchaseRequestItem::class);
    }
}
