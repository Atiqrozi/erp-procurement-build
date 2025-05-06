<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateExistingSuppliersWithDivisionId extends Migration
{
    public function up(): void
    {
        // Ambil ID divisi Produksi menggunakan query langsung
        $divisionId = DB::table('divisions')->where('name', 'Produksi')->value('id');

        if ($divisionId) {
            // Perbarui semua supplier yang ada dengan division_id milik Produksi
            DB::table('suppliers')->update(['division_id' => $divisionId]);
        }
    }

    public function down(): void
    {
        // Kembalikan division_id menjadi null untuk rollback
        DB::table('suppliers')->update(['division_id' => null]);
    }
}