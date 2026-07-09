<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientManageController extends Controller
{
    public function index()
    {
        $patients = Patient::orderByDesc('created_at')->paginate(15);
        return view('admin.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('admin.patients.form');
    }

    public function store(Request $request)
    {
        return back()->with('info', 'Funcionalidade em desenvolvimento.');
    }

    public function show($id)
    {
        $patient = Patient::findOrFail($id);
        return view('admin.patients.show', compact('patient'));
    }

    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return view('admin.patients.form', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        return back()->with('info', 'Funcionalidade em desenvolvimento.');
    }

    public function toggleStatus($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->update(['is_active' => !$patient->is_active]);
        return back()->with('success', 'Status atualizado.');
    }

    public function destroy($id)
    {
        Patient::findOrFail($id)->delete();
        return back()->with('success', 'Paciente excluído.');
    }
}