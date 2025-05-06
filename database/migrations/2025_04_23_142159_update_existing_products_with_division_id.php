<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExistingProductsWithDivisionId extends Migration
{
    public function up(): void
    {
        // Ambil ID divisi Produksi
        $divisionId = \App\Models\Division::where('name', 'Produksi')->first()->id;

        // Perbarui semua produk yang ada dengan division_id milik Produksi
        \App\Models\Product::query()->update(['division_id' => $divisionId]);
    }

    public function down(): void
    {
        // Kembalikan division_id menjadi null untuk rollback
        \App\Models\Product::query()->update(['division_id' => null]);
    }
}
