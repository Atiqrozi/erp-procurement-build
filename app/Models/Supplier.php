<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'contact', 'email', 'address', 'status'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'supplier_products')->withPivot('price');
    }

    // App\Models\Supplier.php

    public function updateStatus($newStatus, $user)
    {
        // Hanya role manager yang bisa ubah status
        if ($user->role !== 'manager') {
            return false;
        }

        // Validasi status yang diizinkan
        $allowedStatuses = ['approved', 'blacklist'];
        if (!in_array($newStatus, $allowedStatuses)) {
            return false;
        }

        $this->status = $newStatus;
        return $this->save();
    }

    public function ratings()
    {
        return $this->hasMany(SupplierRating::class);
    }

    /**
     * Hitung rata-rata rating dalam skala 1–4.
     * Konversi: "sangat baik"=4, "baik"=3, "kurang baik"=2, "tidak baik"=1
     */
    public function getAverageRatingAttribute()
    {
        $map = [
            'sangat baik' => 4,
            'baik' => 3,
            'kurang baik' => 2,
            'tidak baik' => 1,
        ];

        $values = $this->ratings
            ->map(fn($r) => $map[$r->ketepatan] ?? 0)
            ->merge($this->ratings
                ->map(fn($r) => $map[$r->kualitas] ?? 0))
            ->merge($this->ratings
                ->map(fn($r) => $map[$r->harga] ?? 0))
            ->merge($this->ratings
                ->map(fn($r) => $map[$r->layanan] ?? 0))
            ->filter(fn($v) => $v > 0);

        if ($values->isEmpty()) {
            return null;
        }

        return round($values->avg(), 2);
    }
}
