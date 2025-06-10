<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Balance;
use App\Models\PurchaseOrder;

class BalanceController extends Controller
{
    public function index()
    {
        $balances = Balance::with('purchaseOrder')->latest()->get();

        $totalIncome = $balances->sum('income');
        $totalExpense = $balances->sum('expense');
        $balance = $totalIncome - $totalExpense;

        return view('balance.index', compact('balances', 'totalIncome', 'totalExpense', 'balance'));
    }

    public function create()
    {
        $purchaseOrders = PurchaseOrder::all();
        return view('balance.create', compact('purchaseOrders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'income' => 'nullable|numeric|min:0',
            'expense' => 'nullable|numeric|min:0',
            'information' => 'nullable|string|max:255',
            'purchase_orders_id' => 'nullable|exists:purchase_orders,id',
        ]);

        Balance::create($request->all());

        return redirect()->route('balance.index')->with('success', 'Balance entry created successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $balance = Balance::findOrFail($id);
        $balance->status = $request->status;
        $balance->save();

        return redirect()->back()->with('success', 'Status balance berhasil diperbarui.');
    }


    public function destroy(Balance $balance)
    {
        $balance->delete();
        return redirect()->route('balance.index')->with('success', 'Balance entry deleted.');
    }
}
