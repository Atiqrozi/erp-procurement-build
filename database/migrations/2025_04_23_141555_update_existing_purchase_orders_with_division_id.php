<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $divisionId = \App\Models\Division::where('name', 'Produksi')->first()->id;

        \App\Models\PurchaseOrder::query()->update(['division_id' => $divisionId]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\PurchaseOrder::query()->update(['division_id' => null]);
    }
};
