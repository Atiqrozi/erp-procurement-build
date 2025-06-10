<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseOrder;

class Balance extends Model
{
    use HasFactory;

    protected $table = 'balance';

    protected $fillable = [
        'income',
        'expense',
        'information',
        'purchase_orders_id',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_orders_id');
    }
}
