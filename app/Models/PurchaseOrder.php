<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_request_id', 'user_id', 'division_id', 'status', 'total_amount'];

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
    public function goodsReceipts()
    {
        return $this->hasMany(GoodsReceipt::class);
    }
}
