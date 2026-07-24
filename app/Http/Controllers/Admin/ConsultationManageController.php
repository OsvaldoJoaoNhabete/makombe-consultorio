<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Patient;
use App\Models\User;
use App\Models\Insurance;
use App\Models\Specialty;
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
        $date = $request->input('date', today()->format('Y-m-d'));
        $status = $request->input('status', 'all');
        $type = $request->input('type', 'all');
        $doctorId = $request->input('doctor_id', 'all');

        $query = Consultation::with(['patient', 'doctor', 'specialty', 'insurance']);

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

        $consultations = $query->orderBy('scheduled_at', 'asc')->paginate(15)->withQueryString();

        $doctors = User::role('Medico')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $stats = [
            'total' => Consultation::whereDate('scheduled_at', $date)->count(),
            'hoje' => Consultation::whereDate('scheduled_at', today())->count(),
            'agendadas' => Consultation::whereDate('scheduled_at', $date)->whereIn('status', ['agendada', 'confirmada'])->count(),
            'concluidas' => Consultation::whereDate('scheduled_at', $date)->where('status', 'concluida')->count(),
            'canceladas' => Consultation::whereDate('scheduled_at', $date)->where('status', 'cancelada')->count(),
        ];

        return view('admin.consultations.index', compact('consultations', 'stats', 'doctors', 'date', 'status', 'type', 'doctorId'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        $patients = Patient::where('is_active', true)->orderBy('full_name')->get();
        $doctors = User::role('Medico')->where('is_active', true)->with('specialty')->orderBy('name')->get();
        $specialties = Specialty::where('is_active', true)->orderBy('name')->get();
        $insurances = Insurance::where('is_active', true)->orderBy('name')->get();

        return view('admin.consultations.form', compact('patients', 'doctors', 'specialties', 'insurances'));
    }

    /**
     * Salvar nova consulta
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:users,id'],
            'specialty_id' => ['nullable', 'exists:specialties,id'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'type' => ['required', 'in:presencial,teleconsulta,domicilio'],
            'is_urgent' => ['nullable', 'boolean'],
            'payment_method' => ['required', 'in:numerario,mobile_money,pos_tpa,transferencia,seguro'],
            'payment_provider' => ['nullable', 'string', 'max:100'],
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'home_visit_address' => ['required_if:type,domicilio', 'nullable', 'string', 'max:500'],
        ], [
            'home_visit_address.required_if' => 'O endereço completo é obrigatório para consultas ao domicílio.',
        ]);

        // Verificar conflito de horário
        $conflict = Consultation::where('doctor_id', $validated['doctor_id'])
            ->where('scheduled_at', $validated['scheduled_at'])
            ->whereNotIn('status', ['cancelada', 'faltou'])
            ->exists();

        if ($conflict) {
            return back()
                ->withErrors(['scheduled_at' => 'Este horário já está ocupado para o médico selecionado.'])
                ->withInput();
        }

        // Sugestão 11: Gerar link Jitsi simples e direto para teleconsulta
        $location = null;
        if ($validated['type'] === 'teleconsulta') {
            $roomName = 'makombe-' . $validated['patient_id'] . '-' . time();
            $location = 'https://meet.jit.si/' . $roomName;
        }

        $consultation = Consultation::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'],
            'specialty_id' => $validated['specialty_id'] ?? null,
            'scheduled_at' => $validated['scheduled_at'],
            'type' => $validated['type'],
            'is_urgent' => $request->has('is_urgent'),
            'payment_method' => $validated['payment_method'],
            'payment_provider' => $validated['payment_provider'] ?? null,
            'payment_reference' => $validated['payment_reference'] ?? null,
            'total_amount' => $validated['total_amount'] ?? 0,
            'notes' => $validated['notes'] ?? null,
            'home_visit_address' => $validated['home_visit_address'] ?? null,
            'location' => $location,
            'status' => 'agendada',
            'payment_status' => 'pendente',
            'created_by' => Auth::id(),
        ]);

        // Registar atividade (opcional, se o modelo PatientActivityLog existir)
        if (class_exists(PatientActivityLog::class)) {
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
        $consultation = Consultation::with(['patient', 'doctor.specialty', 'insurance', 'createdBy', 'rating'])
            ->findOrFail($id);

        // Histórico recente do paciente (últimas 5 consultas concluídas)
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
        $doctors = User::role('Medico')->where('is_active', true)->with('specialty')->orderBy('name')->get();
        $specialties = Specialty::where('is_active', true)->orderBy('name')->get();
        $insurances = Insurance::where('is_active', true)->orderBy('name')->get();

        return view('admin.consultations.form', compact('consultation', 'patients', 'doctors', 'specialties', 'insurances'));
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
            'specialty_id' => ['nullable', 'exists:specialties,id'],
            'scheduled_at' => ['required', 'date'],
            'type' => ['required', 'in:presencial,teleconsulta,domicilio'],
            'is_urgent' => ['nullable', 'boolean'],
            'payment_method' => ['required', 'in:numerario,mobile_money,pos_tpa,transferencia,seguro'],
            'payment_provider' => ['nullable', 'string', 'max:100'],
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'home_visit_address' => ['required_if:type,domicilio', 'nullable', 'string', 'max:500'],
            'status' => ['required', 'in:agendada,confirmada,em_andamento,concluida,cancelada,faltou'],
        ]);

        // Verificar conflito de horário (excluindo a própria consulta)
        $conflict = Consultation::where('doctor_id', $validated['doctor_id'])
            ->where('scheduled_at', $validated['scheduled_at'])
            ->where('id', '!=', $consultation->id)
            ->whereNotIn('status', ['cancelada', 'faltou'])
            ->exists();

        if ($conflict) {
            return back()
                ->withErrors(['scheduled_at' => 'Este horário já está ocupado para o médico selecionado.'])
                ->withInput();
        }

        // Gerar link Jitsi se mudar para teleconsulta e ainda não tiver link
        if ($validated['type'] === 'teleconsulta' && !$consultation->location) {
            $roomName = 'makombe-' . $consultation->id . '-' . time();
            $validated['location'] = 'https://meet.jit.si/' . $roomName;
        } elseif ($validated['type'] !== 'teleconsulta') {
            // Se mudar de teleconsulta para presencial/domicílio, limpar o link
            $validated['location'] = null;
        }

        $validated['is_urgent'] = $request->has('is_urgent');

        $consultation->update($validated);

        return redirect()
            ->route('consultations.show', $consultation->id)
            ->with('success', '✅ Consulta atualizada com sucesso!');
    }

    /**
     * Sugestão 8: Imprimir Nota Médica (Prescrição e Exames)
     */
    public function printMedicalNote($id)
    {
        $consultation = Consultation::with(['patient', 'doctor.specialty'])->findOrFail($id);

        // Opcional: Verificar se a consulta já foi concluída ou se tem dados clínicos
        // if (!$consultation->prescription && !$consultation->exams) { ... }

        return view('admin.consultations.print-note', compact('consultation'));
    }

    /**
     * Concluir consulta
     */
    public function complete($id)
    {
        $consultation = Consultation::findOrFail($id);

        if ($consultation->status === 'concluida') {
            return back()->with('error', 'Esta consulta já está marcada como concluída.');
        }

        $consultation->update(['status' => 'concluida']);

        if (class_exists(PatientActivityLog::class)) {
            try {
                PatientActivityLog::log(
                    $consultation->patient_id,
                    'consulta_concluida',
                    "Consulta concluída",
                    ['consultation_id' => $consultation->id],
                    Auth::id()
                );
            } catch (\Exception $e) {
                Log::warning('Erro ao registrar log: ' . $e->getMessage());
            }
        }

        return back()->with('success', '✅ Consulta marcada como concluída!');
    }

    /**
     * Cancelar consulta
     */
    public function cancel($id)
    {
        $consultation = Consultation::findOrFail($id);

        if (in_array($consultation->status, ['concluida', 'cancelada', 'faltou'])) {
            return back()->with('error', 'Esta consulta não pode ser cancelada neste estado.');
        }

        $consultation->update(['status' => 'cancelada']);

        if (class_exists(PatientActivityLog::class)) {
            try {
                PatientActivityLog::log(
                    $consultation->patient_id,
                    'consulta_cancelada',
                    "Consulta cancelada pela gestão",
                    ['consultation_id' => $consultation->id],
                    Auth::id()
                );
            } catch (\Exception $e) {
                Log::warning('Erro ao registrar log: ' . $e->getMessage());
            }
        }

        return back()->with('success', '✅ Consulta cancelada com sucesso.');
    }

    /**
     * Marcar como "Faltou" (No-show)
     */
    public function markAsNoShow($id)
    {
        $consultation = Consultation::findOrFail($id);

        if (in_array($consultation->status, ['concluida', 'cancelada', 'faltou'])) {
            return back()->with('error', 'Estado inválido para esta ação.');
        }

        $consultation->update(['status' => 'faltou']);

        return back()->with('success', '✅ Paciente marcado como "Faltou".');
    }

    /**
     * Excluir consulta (Soft Delete)
     */
    public function destroy($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->delete();

        return redirect()
            ->route('consultations.index')
            ->with('success', '✅ Consulta excluída do sistema.');
    }
}