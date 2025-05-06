<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'contact', 'email', 'address', 'division_id'];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'supplier_products')->withPivot('price');
    }
}
