<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['name', 'contact', 'email', 'address'];
    use HasFactory;
    public function products()
    {
        return $this->belongsToMany(Product::class, 'supplier_products')
                    ->withPivot('price')
                    ->withTimestamps();
    }
}
