<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Patient;
use App\Models\User;
use App\Models\Insurance;
use App\Models\PatientActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ConsultationManageController extends Controller
{
    /**
     * Lista de consultas com filtros
     */
    public function index(Request $request)
    {
        $date = $request->input('date');
        $status = $request->input('status', 'all');
        $type = $request->input('type', 'all');
        $doctorId = $request->input('doctor_id', 'all');

        $query = Consultation::with(['patient', 'doctor', 'insurance']);

        if ($date) {
            $query->whereDate('scheduled_at', $date);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        if ($doctorId !== 'all') {
            $query->where('doctor_id', $doctorId);
        }

        $consultations = $query->orderByDesc('scheduled_at')->paginate(15)->withQueryString();

        $doctors = User::role('Medico')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $stats = [
            'total' => Consultation::count(),
            'hoje' => Consultation::whereDate('scheduled_at', today())->count(),
            'agendadas' => Consultation::whereIn('status', ['agendada', 'confirmada'])->count(),
            'concluidas' => Consultation::where('status', 'concluida')->count(),
            'canceladas' => Consultation::where('status', 'cancelada')->count(),
        ];

        return view('admin.consultations.index', compact('consultations', 'stats', 'doctors', 'date', 'status', 'type', 'doctorId'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        $patients = Patient::where('is_active', true)
            ->orderBy('full_name')
            ->get();
        
        $doctors = User::role('Medico')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        $insurances = Insurance::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.consultations.form', compact('patients', 'doctors', 'insurances'));
    }

    /**
     * Salvar nova consulta
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:users,id'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'type' => ['required', 'in:presencial,teleconsulta,domicilio'],
            'insurance_id' => ['nullable', 'exists:insurances,id'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ], [
            'patient_id.required' => 'Selecione um paciente.',
            'doctor_id.required' => 'Selecione um médico.',
            'scheduled_at.required' => 'A data/hora é obrigatória.',
            'scheduled_at.after' => 'A data deve ser futura.',
            'type.required' => 'Selecione o tipo de consulta.',
        ]);

        // Verificar conflito de horário
        $conflict = Consultation::where('doctor_id', $validated['doctor_id'])
            ->where('scheduled_at', $validated['scheduled_at'])
            ->whereNotIn('status', ['cancelada'])
            ->exists();

        if ($conflict) {
            return back()
                ->withErrors(['scheduled_at' => 'Este horário já está ocupado para o médico selecionado.'])
                ->withInput();
        }

        // Gerar link Jitsi para teleconsulta
        $location = null;
        if ($validated['type'] === 'teleconsulta') {
            $roomName = 'makombe-' . time() . '-' . uniqid();
            $location = 'https://meet.jit.si/' . $roomName;
        }

        $consultation = Consultation::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'],
            'scheduled_at' => $validated['scheduled_at'],
            'type' => $validated['type'],
            'insurance_id' => $validated['insurance_id'] ?? null,
            'total_amount' => $validated['total_amount'] ?? 0,
            'notes' => $validated['notes'] ?? null,
            'location' => $location,
            'status' => 'agendada',
            'payment_status' => 'pendente',
            'created_by' => Auth::id(),
        ]);

        // Registrar atividade
        try {
            PatientActivityLog::log(
                $validated['patient_id'],
                'consulta_agendada',
                "Consulta agendada para " . Carbon::parse($validated['scheduled_at'])->format('d/m/Y \à\s H:i'),
                ['consultation_id' => $consultation->id, 'type' => $validated['type']],
                Auth::id()
            );
        } catch (\Exception $e) {
            Log::warning('Erro ao registrar log: ' . $e->getMessage());
        }

        return redirect()
            ->route('consultations.show', $consultation->id)
            ->with('success', '✅ Consulta agendada com sucesso!');
    }

    /**
     * Detalhes da consulta
     */
    public function show($id)
    {
        $consultation = Consultation::with(['patient.insurances', 'doctor', 'insurance', 'createdBy'])
            ->findOrFail($id);

        $patientHistory = Consultation::where('patient_id', $consultation->patient_id)
            ->where('id', '!=', $consultation->id)
            ->where('status', 'concluida')
            ->with('doctor')
            ->orderByDesc('scheduled_at')
            ->limit(5)
            ->get();

        return view('admin.consultations.show', compact('consultation', 'patientHistory'));
    }

    /**
     * Formulário de edição
     */
    public function edit($id)
    {
        $consultation = Consultation::findOrFail($id);
        
        $patients = Patient::where('is_active', true)->orderBy('full_name')->get();
        $doctors = User::role('Medico')->where('is_active', true)->orderBy('name')->get();
        $insurances = Insurance::where('is_active', true)->orderBy('name')->get();

        return view('admin.consultations.form', compact('consultation', 'patients', 'doctors', 'insurances'));
    }

    /**
     * Atualizar consulta
     */
    public function update(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);

        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:users,id'],
            'scheduled_at' => ['required', 'date'],
            'type' => ['required', 'in:presencial,teleconsulta,domicilio'],
            'insurance_id' => ['nullable', 'exists:insurances,id'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:agendada,confirmada,em_andamento,concluida,cancelada,faltou'],
        ]);

        // Verificar conflito de horário (exceto a própria consulta)
        $conflict = Consultation::where('doctor_id', $validated['doctor_id'])
            ->where('scheduled_at', $validated['scheduled_at'])
            ->where('id', '!=', $consultation->id)
            ->whereNotIn('status', ['cancelada'])
            ->exists();

        if ($conflict) {
            return back()
                ->withErrors(['scheduled_at' => 'Este horário já está ocupado.'])
                ->withInput();
        }

        // Gerar link Jitsi se mudar para teleconsulta
        if ($validated['type'] === 'teleconsulta' && !$consultation->location) {
            $roomName = 'makombe-' . $consultation->id . '-' . uniqid();
            $validated['location'] = 'https://meet.jit.si/' . $roomName;
        } elseif ($validated['type'] !== 'teleconsulta') {
            $validated['location'] = null;
        }

        $consultation->update($validated);

        return redirect()
            ->route('consultations.show', $consultation->id)
            ->with('success', '✅ Consulta atualizada!');
    }

    /**
     * Concluir consulta
     */
    public function complete($id)
    {
        $consultation = Consultation::findOrFail($id);

        if ($consultation->status === 'concluida') {
            return back()->with('error', 'Esta consulta já está concluída.');
        }

        $consultation->update(['status' => 'concluida']);

        try {
            PatientActivityLog::log(
                $consultation->patient_id,
                'consulta_concluida',
                "Consulta concluída",
                ['consultation_id' => $consultation->id],
                Auth::id()
            );
        } catch (\Exception $e) {
            // Ignorar erro
        }

        return back()->with('success', '✅ Consulta marcada como concluída!');
    }

    /**
     * Cancelar consulta
     */
    public function cancel($id)
    {
        $consultation = Consultation::findOrFail($id);

        if (in_array($consultation->status, ['concluida', 'cancelada'])) {
            return back()->with('error', 'Esta consulta não pode ser cancelada.');
        }

        $consultation->update(['status' => 'cancelada']);

        try {
            PatientActivityLog::log(
                $consultation->patient_id,
                'consulta_cancelada',
                "Consulta cancelada",
                ['consultation_id' => $consultation->id],
                Auth::id()
            );
        } catch (\Exception $e) {
            // Ignorar erro
        }

        return back()->with('success', 'Consulta cancelada.');
    }

    /**
     * Excluir consulta
     */
    public function destroy($id)
    {
        Consultation::findOrFail($id)->delete();
        return redirect()
            ->route('consultations.index')
            ->with('success', '✅ Consulta excluída.');
    }
}