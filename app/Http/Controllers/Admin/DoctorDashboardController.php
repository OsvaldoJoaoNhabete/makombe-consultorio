<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\Consultation;
use App\Models\PatientActivityLog;

class DoctorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $doctor = Auth::user();
        $filterDate = $request->input('date', today()->format('Y-m-d'));
        $filterStatus = $request->input('status', 'all');

        $query = Consultation::where('doctor_id', $doctor->id)
            ->with(['patient', 'insurance']);

        if ($filterDate) {
            $query->whereDate('scheduled_at', $filterDate);
        }

        if ($filterStatus !== 'all') {
            $query->where('status', $filterStatus);
        }

        $consultations = $query->orderBy('scheduled_at')->get();

        $stats = [
            'total' => Consultation::where('doctor_id', $doctor->id)->count(),
            'hoje' => Consultation::where('doctor_id', $doctor->id)
                ->whereDate('scheduled_at', today())->count(),
            'agendadas' => Consultation::where('doctor_id', $doctor->id)
                ->where('status', 'agendada')->count(),
            'concluidas' => Consultation::where('doctor_id', $doctor->id)
                ->where('status', 'concluida')->count(),
        ];

        return view('admin.doctor.index', compact('consultations', 'stats', 'filterDate', 'filterStatus'));
    }

    public function show($id)
    {
        $doctor = Auth::user();
        
        $consultation = Consultation::where('doctor_id', $doctor->id)
            ->where('id', $id)
            ->with(['patient', 'insurance', 'createdBy'])
            ->firstOrFail();

        $patientHistory = Consultation::where('patient_id', $consultation->patient_id)
            ->where('id', '!=', $consultation->id)
            ->where('status', 'concluida')
            ->with('doctor')
            ->orderByDesc('scheduled_at')
            ->limit(5)
            ->get();

        return view('admin.doctor.show', compact('doctor', 'consultation', 'patientHistory'));
    }

    public function attend($id)
    {
        $doctor = Auth::user();
        
        $consultation = Consultation::where('doctor_id', $doctor->id)
            ->where('id', $id)
            ->whereIn('status', ['agendada', 'confirmada', 'em_andamento'])
            ->with(['patient', 'insurance'])
            ->firstOrFail();

        if ($consultation->type === 'teleconsulta') {
            if (!$consultation->location || !str_starts_with($consultation->location, 'http')) {
                $roomName = 'makombe-' . $consultation->id . '-' . uniqid();
                $consultation->update(['location' => 'https://meet.jit.si/' . $roomName]);
                $consultation->location = 'https://meet.jit.si/' . $roomName;
            }
        }

        if ($consultation->status === 'agendada') {
            $consultation->update(['status' => 'em_andamento']);
        }
        
        return view('admin.doctor.attend', compact('doctor', 'consultation'));
    }

    public function storeAttendance(Request $request, $id)
    {
        $doctor = Auth::user();
        
        $consultation = Consultation::where('doctor_id', $doctor->id)
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'diagnosis' => 'required|string|max:2000',
            'prescription' => 'nullable|string|max:5000',
            'observations' => 'nullable|string|max:2000',
            'total_amount' => 'nullable|numeric|min:0',
        ]);

        $action = $request->input('action', 'save');

        $consultation->update([
            'diagnosis' => $validated['diagnosis'],
            'prescription' => $validated['prescription'] ?? null,
            'observations' => $validated['observations'] ?? null,
            'total_amount' => $validated['total_amount'] ?? $consultation->total_amount,
            'status' => $action === 'complete' ? 'concluida' : 'em_andamento',
        ]);

        try {
            PatientActivityLog::log(
                $consultation->patient_id,
                'consulta_concluida',
                "Consulta concluída pelo Dr(a). {$doctor->name}",
                ['consultation_id' => $consultation->id]
            );
        } catch (\Exception $e) {
            Log::warning('Erro ao registrar log', ['error' => $e->getMessage()]);
        }

        return redirect()
            ->route('doctor.show', $consultation->id)
            ->with('success', $action === 'complete' ? '✅ Consulta concluída!' : '✅ Atendimento salvo!');
    }

    public function complete($id)
    {
        $doctor = Auth::user();
        
        $consultation = Consultation::where('doctor_id', $doctor->id)
            ->where('id', $id)
            ->firstOrFail();

        if ($consultation->status === 'concluida') {
            return back()->with('error', 'Esta consulta já foi concluída.');
        }

        $consultation->update(['status' => 'concluida']);

        return back()->with('success', '✅ Consulta marcada como concluída!');
    }

    public function cancel($id)
    {
        $doctor = Auth::user();
        
        $consultation = Consultation::where('doctor_id', $doctor->id)
            ->where('id', $id)
            ->firstOrFail();

        if (in_array($consultation->status, ['concluida', 'cancelada'])) {
            return back()->with('error', 'Esta consulta não pode ser cancelada.');
        }

        $consultation->update(['status' => 'cancelada']);

        return back()->with('success', 'Consulta cancelada.');
    }

    public function startVideoCall($id)
    {
        $doctor = Auth::user();
        
        $consultation = Consultation::where('doctor_id', $doctor->id)
            ->where('id', $id)
            ->where('type', 'teleconsulta')
            ->firstOrFail();

        if (!$consultation->location || !str_starts_with($consultation->location, 'http')) {
            $roomName = 'makombe-' . $consultation->id . '-' . uniqid();
            $consultation->update(['location' => 'https://meet.jit.si/' . $roomName]);
        }

        $consultation->startVideoCall();

        return back()->with('success', '✅ Videochamada iniciada! Paciente notificado.');
    }
}