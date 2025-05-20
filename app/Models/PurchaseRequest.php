<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'division_id', 'status'];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseRequestItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
