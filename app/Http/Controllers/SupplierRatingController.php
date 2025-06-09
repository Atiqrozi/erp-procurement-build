<?php

// app/Http/Controllers/SupplierRatingController.php

namespace App\Http\Controllers;

use App\Models\SupplierRating;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierRatingController extends Controller
{
    public function index()
    {
        $ratings = SupplierRating::with('supplier')->get();
        return view('supplier_rating.index', compact('ratings'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('supplier_rating.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'ketepatan' => 'nullable|in:sangat baik,baik,kurang baik,tidak baik',
            'kualitas' => 'nullable|in:sangat baik,baik,kurang baik,tidak baik',
            'harga' => 'nullable|in:sangat baik,baik,kurang baik,tidak baik',
            'layanan' => 'nullable|in:sangat baik,baik,kurang baik,tidak baik',
        ]);

        SupplierRating::create($request->all());

        return redirect()->route('supplier_rating.index')->with('success', 'Rating berhasil ditambahkan.');
    }

    public function edit(SupplierRating $supplier_rating)
    {
        return view('supplier_rating.edit', compact('supplier_rating'));
    }

    public function update(Request $request, SupplierRating $supplier_rating)
    {
        $request->validate([
            'ketepatan' => 'nullable|in:sangat baik,baik,kurang baik,tidak baik',
            'kualitas' => 'nullable|in:sangat baik,baik,kurang baik,tidak baik',
            'harga' => 'nullable|in:sangat baik,baik,kurang baik,tidak baik',
            'layanan' => 'nullable|in:sangat baik,baik,kurang baik,tidak baik',
        ]);

        $supplier_rating->update($request->only(['ketepatan', 'kualitas', 'harga', 'layanan']));

        return redirect()->route('supplier_rating.index')->with('success', 'Rating berhasil diperbarui.');
    }
}

