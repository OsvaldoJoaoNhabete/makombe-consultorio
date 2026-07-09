<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Insurance;
use Illuminate\Http\Request;

class InsuranceManageController extends Controller
{
    public function index()
    {
        $insurances = Insurance::orderBy('name')->paginate(15);
        $stats = [
            'total' => Insurance::count(),
            'ativas' => Insurance::where('is_active', true)->count(),
            'inativas' => Insurance::where('is_active', false)->count(),
        ];
        return view('admin.insurances.index', compact('insurances', 'stats'));
    }

    public function create()
    {
        return view('admin.insurances.form');
    }

    public function store(Request $request)
    {
        return back()->with('info', 'Funcionalidade em desenvolvimento.');
    }

    public function show($id)
    {
        $insurance = Insurance::findOrFail($id);
        return view('admin.insurances.show', compact('insurance'));
    }

    public function edit($id)
    {
        $insurance = Insurance::findOrFail($id);
        return view('admin.insurances.form', compact('insurance'));
    }

    public function update(Request $request, $id)
    {
        return back()->with('info', 'Funcionalidade em desenvolvimento.');
    }

    public function toggleStatus($id)
    {
        $insurance = Insurance::findOrFail($id);
        $insurance->update(['is_active' => !$insurance->is_active]);
        return back()->with('success', 'Status atualizado.');
    }

    public function destroy($id)
    {
        Insurance::findOrFail($id)->delete();
        return back()->with('success', 'Seguradora excluída.');
    }
}