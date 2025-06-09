<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with(['products', 'ratings'])->where('status', '!=', 'blacklist')->get();
        return view('suppliers.index', compact('suppliers'));
    }

    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }


    public function create()
    {
        $products = Product::all();
        return view('suppliers.create', compact('products'));
    }

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

        $supplier = Supplier::create([
            'name' => $request->name,
            'contact' => $request->contact,
            'email' => $request->email,
            'address' => $request->address,
        ]);


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

    public function edit(Supplier $supplier)
    {
        $products = Product::all();
        return view('suppliers.edit', compact('supplier', 'products'));
    }

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

        $updateData = $request->only(['name', 'contact', 'email', 'address']);

        $supplier->update($updateData);

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

    public function destroy(Supplier $supplier)
    {
        $supplier->products()->detach();
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted.');
    }

    // App\Http\Controllers\SupplierController.php

    public function changeStatus(Request $request, Supplier $supplier)
    {
        $request->validate([
            'status' => 'required|in:approved,blacklist',
        ]);

        $success = $supplier->updateStatus($request->status, auth()->user());

        if ($success) {
            return redirect()->route('suppliers.index')->with('success', 'Status supplier berhasil diperbarui.');
        } else {
            return redirect()->route('suppliers.index')->with('error', 'Anda tidak memiliki izin atau status tidak valid.');
        }
    }


    public function blacklist()
    {
        $suppliers = Supplier::where('status', 'blacklist')->get();
        return view('blacklist.index', compact('suppliers'));
    }


}
