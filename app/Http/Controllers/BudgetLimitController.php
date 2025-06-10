<?php

namespace App\Http\Controllers;

use App\Models\BudgetLimit;
use Illuminate\Http\Request;

class BudgetLimitController extends Controller
{
    public function index()
    {
        $budgets = BudgetLimit::all();
        return view('budget_limits.index', compact('budgets'));
    }

    public function create()
    {
        return view('budget_limits.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'limit' => 'required|numeric',
        ]);

        BudgetLimit::create([
            'limit' => $request->limit,
            'status' => 'pending',
            'active' => false,
        ]);

        return redirect()->route('budget_limits.index')->with('success', 'Budget limit berhasil ditambahkan.');
    }


    public function toggleActive(Request $request, $id)
    {
        $budget = BudgetLimit::findOrFail($id);
        $budget->active = $request->has('active') ? 1 : 0;
        $budget->save();

        return back()->with('success', 'Status aktif berhasil diperbarui.');
    }

    public function updateStatus($id, $status)
    {
        $budget = BudgetLimit::findOrFail($id);

        // Validasi status yang boleh
        if (!in_array($status, ['approved', 'rejected'])) {
            return back()->with('error', 'Status tidak valid.');
        }

        $budget->status = $status;
        $budget->save();

        return redirect()->route('budget_limits.index')->with('success', "Status budget limit berhasil diubah menjadi $status.");
    }


    public function destroy($id)
    {
        BudgetLimit::destroy($id);
        return back()->with('success', 'Budget Limit berhasil dihapus.');
    }
}
