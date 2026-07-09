<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class ConsultationManageController extends Controller
{
    public function index()
    {
        $consultations = Consultation::with(['patient', 'doctor'])
            ->orderByDesc('scheduled_at')
            ->paginate(15);
        return view('admin.consultations.index', compact('consultations'));
    }

    public function create()
    {
        $patients = Patient::orderBy('full_name')->get();
        $doctors = User::role('Medico')->where('is_active', true)->get();
        return view('admin.consultations.form', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        return back()->with('info', 'Funcionalidade em desenvolvimento.');
    }

    public function show($id)
    {
        $consultation = Consultation::with(['patient', 'doctor', 'insurance'])->findOrFail($id);
        return view('admin.consultations.show', compact('consultation'));
    }

    public function edit($id)
    {
        $consultation = Consultation::findOrFail($id);
        $patients = Patient::orderBy('full_name')->get();
        $doctors = User::role('Medico')->where('is_active', true)->get();
        return view('admin.consultations.form', compact('consultation', 'patients', 'doctors'));
    }

    public function update(Request $request, $id)
    {
        return back()->with('info', 'Funcionalidade em desenvolvimento.');
    }

    public function complete($id)
    {
        Consultation::findOrFail($id)->update(['status' => 'concluida']);
        return back()->with('success', 'Consulta concluída.');
    }

    public function cancel($id)
    {
        Consultation::findOrFail($id)->update(['status' => 'cancelada']);
        return back()->with('success', 'Consulta cancelada.');
    }

    public function destroy($id)
    {
        Consultation::findOrFail($id)->delete();
        return back()->with('success', 'Consulta excluída.');
    }
}