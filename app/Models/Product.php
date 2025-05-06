<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Pastikan kolom division_id ada di $fillable
    protected $fillable = ['name', 'sku', 'description', 'unit', 'division_id'];

    // Relasi ke tabel suppliers
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_products')
                    ->withPivot('price')
                    ->withTimestamps();
    }

    // Relasi ke tabel purchase_request_items
    public function purchaseRequestItems()
    {
        return $this->hasMany(PurchaseRequestItem::class);
    }

    // Relasi ke tabel divisions
    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
