<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil supplier berdasarkan divisi user yang sedang login
        $suppliers = Supplier::where('division_id', auth()->user()->division_id)->with('products')->get();

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('division_id', auth()->user()->division_id)->get();
        return view('suppliers.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'product_ids' => 'array',
            'product_prices' => 'array',
            'product_prices.*' => 'nullable|numeric|min:0',
        ]);

        // Simpan data supplier
        $supplier = Supplier::create([
        'name' => $request->name,
        'contact' => $request->contact,
        'email' => $request->email,
        'address' => $request->address,
        'division_id' => auth()->user()->division_id, // Ambil divisi dari user yang login
        ]);

        // Sinkronisasi produk dan harga
        $products = [];
        if ($request->has('product_ids')) {
            foreach ($request->product_ids as $productId) {
                $products[$productId] = [
                    'price' => $request->product_prices[$productId] ?? 0,
                ];
            }
        }
        $supplier->products()->sync($products);

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $products = Product::all();
        return view('suppliers.edit', compact('supplier', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'product_ids' => 'array',
            'product_prices' => 'array',
            'product_prices.*' => 'nullable|numeric|min:0',
        ]);

        // Update data supplier
        $supplier->update($request->only(['name', 'contact', 'email', 'address']));

        // Sinkronisasi produk dan harga
        $products = [];
        if ($request->has('product_ids')) {
            foreach ($request->product_ids as $productId) {
                $products[$productId] = [
                    'price' => $request->product_prices[$productId] ?? 0,
                ];
            }
        }
        $supplier->products()->sync($products);

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->products()->detach(); // hapus relasi produk
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted.');
    }
}
