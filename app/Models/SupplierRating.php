<?php

// app/Models/SupplierRating.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierRating extends Model
{
    use HasFactory;

    protected $table = 'supplier_rating';

    protected $fillable = [
        'supplier_id',
        'ketepatan',
        'kualitas',
        'harga',
        'layanan',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}

