<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    public function run()
    {
        Division::create(['name' => 'HR', 'description' => 'Divisi Sumber Daya Manusia']);
        Division::create(['name' => 'Keuangan', 'description' => 'Divisi Keuangan']);
        Division::create(['name' => 'Produksi', 'description' => 'Divisi Produksi']);
        Division::create(['name' => 'Penjualan', 'description' => 'Divisi Penjualan']);
        Division::create(['name' => 'Operasional', 'description' => 'Divisi Operasional']);
    }
}
