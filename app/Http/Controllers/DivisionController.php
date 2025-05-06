<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::all();
        return view('index', compact('divisions'));
    }

    public function show(Division $division)
    {
        if (auth()->user()->division_id !== $division->id) {
            abort(403, 'Anda tidak memiliki akses ke divisi ini.');
        }

        return view('divisions.dashboard', compact('division'));
    }
}
