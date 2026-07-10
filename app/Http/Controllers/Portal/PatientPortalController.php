<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Quote;
use App\Models\Payment;
use App\Models\PatientActivityLog;

class PatientPortalController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Mostrar formulário de login do paciente
     */
    public function showLogin()
    {
        return view('portal.login');
    }

    /**
     * Processar login do paciente
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = trim($validated['identifier']);
        $password = $validated['password'];

        $patient = Patient::findByEmailOrPhone($identifier);

        if (!$patient) {
            throw ValidationException::withMessages([
                'identifier' => 'Credenciais não encontradas. Verifique seu email ou telefone.',
            ]);
        }

        if (!$patient->is_active) {
            throw ValidationException::withMessages([
                'identifier' => 'Sua conta está desativada. Contacte o consultório.',
            ]);
        }

        if (!Hash::check($password, $patient->password)) {
            throw ValidationException::withMessages([
                'password' => 'Senha incorreta. Tente novamente.',
            ]);
        }

        Auth::guard('patient')->login($patient, $request->boolean('remember'));

        try {
            PatientActivityLog::log(
                $patient->id,
                'login',
                "Paciente fez login no portal",
                ['ip' => $request->ip()]
            );
        } catch (\Exception $e) {
            // Ignorar erro de log
        }

        return redirect()->route('patient.dashboard');
    }

    /**
     * Dashboard do paciente
     */
    public function dashboard()
    {
        $patient = Auth::guard('patient')->user();

        $upcomingConsultations = Consultation::where('patient_id', $patient->id)
            ->where('scheduled_at', '>=', now())
            ->whereIn('status', ['agendada', 'confirmada', 'em_andamento'])
            ->with('doctor')
            ->orderBy('scheduled_at')
            ->limit(5)
            ->get();

        $pastConsultations = Consultation::where('patient_id', $patient->id)
            ->where('status', 'concluida')
            ->with('doctor')
            ->orderByDesc('scheduled_at')
            ->limit(5)
            ->get();

        $recentQuotes = Quote::where('patient_id', $patient->id)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        $recentPayments = Payment::where('patient_id', $patient->id)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        $stats = [
            'total_consultas' => Consultation::where('patient_id', $patient->id)->count(),
            'consultas_agendadas' => Consultation::where('patient_id', $patient->id)
                ->whereIn('status', ['agendada', 'confirmada', 'em_andamento'])
                ->count(),
            'consultas_concluidas' => Consultation::where('patient_id', $patient->id)
                ->where('status', 'concluida')
                ->count(),
            'total_cotacoes' => Quote::where('patient_id', $patient->id)->count(),
            'total_pago' => Payment::where('patient_id', $patient->id)
                ->where('status', 'confirmado')
                ->sum('amount'),
        ];

        return view('portal.dashboard', compact(
            'patient',
            'upcomingConsultations',
            'pastConsultations',
            'recentQuotes',
            'recentPayments',
            'stats'
        ));
    }

    /**
     * Mostrar formulário de agendamento
     */
    public function showSchedule()
    {
        $patient = Auth::guard('patient')->user();
        
        $doctors = \App\Models\User::role('Medico')
            ->where('is_active', true)
            ->get(['id', 'name']);

        $insurances = \App\Models\Insurance::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'coverage_percentage']);

        $patientInsurances = $patient->insurances()
            ->wherePivot('is_active', true)
            ->get();

        return view('portal.schedule', compact(
            'patient', 
            'doctors', 
            'insurances', 
            'patientInsurances'
        ));
    }

    /**
     * Processar agendamento
     */
    public function schedule(Request $request)
    {
        $patient = Auth::guard('patient')->user();

        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date|after:today',
            'time' => 'required',
            'type' => 'required|in:presencial,teleconsulta,domicilio',
            'insurance_id' => 'nullable|exists:insurances,id',
            'clinical_notes' => 'nullable|string|max:2000',
        ]);

        $scheduledAt = $validated['date'] . ' ' . $validated['time'] . ':00';

        $hour = (int) explode(':', $validated['time'])[0];
        if ($hour < 7 || $hour >= 19) {
            return back()
                ->withErrors(['time' => 'O horário deve ser entre 07:00 e 19:00.'])
                ->withInput();
        }

        $conflict = Consultation::where('doctor_id', $validated['doctor_id'])
            ->where('scheduled_at', $scheduledAt)
            ->where('status', '!=', 'cancelada')
            ->exists();

        if ($conflict) {
            return back()
                ->withErrors(['time' => 'Este horário já está ocupado. Escolha outro.'])
                ->withInput();
        }

        $consultation = Consultation::create([
            'patient_id' => $patient->id,
            'doctor_id' => $validated['doctor_id'],
            'scheduled_at' => $scheduledAt,
            'type' => $validated['type'],
            'insurance_id' => $validated['insurance_id'] ?? null,
            'clinical_notes' => $validated['clinical_notes'] ?? null,
            'status' => 'agendada',
        ]);

        if ($consultation->type === 'teleconsulta') {
            $roomName = 'makombe-consulta-' . $consultation->id . '-' . uniqid();
            $consultation->update(['location' => 'https://meet.jit.si/' . $roomName]);

            try {
                $this->whatsappService->sendConsultationMessage($consultation);
            } catch (\Exception $e) {
                \Log::warning('Erro ao enviar WhatsApp: ' . $e->getMessage());
            }
        }

        try {
            PatientActivityLog::log(
                $patient->id,
                'agendar_consulta',
                "Paciente agendou consulta para " . Carbon::parse($scheduledAt)->format('d/m/Y \à\s H:i'),
                ['consultation_id' => $consultation->id]
            );
        } catch (\Exception $e) {
            // Ignorar erro de log
        }

        return redirect()
            ->route('patient.consultations')
            ->with('success', '✅ Consulta agendada com sucesso! Data: ' . Carbon::parse($scheduledAt)->format('d/m/Y \à\s H:i'));
    }

    /**
     * Lista de consultas do paciente
     */
    public function consultations(Request $request)
    {
        $patient = Auth::guard('patient')->user();
        
        $filter = $request->input('filter', 'all');
        
        $query = Consultation::where('patient_id', $patient->id)
            ->with('doctor', 'insurance');
        
        switch ($filter) {
            case 'upcoming':
                $query->where('scheduled_at', '>=', now())
                      ->whereIn('status', ['agendada', 'confirmada', 'em_andamento']);
                break;
            case 'past':
                $query->where('status', 'concluida');
                break;
            case 'cancelled':
                $query->where('status', 'cancelada');
                break;
        }
        
        $consultations = $query->orderByDesc('scheduled_at')->paginate(10)->withQueryString();
        
        $stats = [
            'total' => Consultation::where('patient_id', $patient->id)->count(),
            'upcoming' => Consultation::where('patient_id', $patient->id)
                ->where('scheduled_at', '>=', now())
                ->whereIn('status', ['agendada', 'confirmada', 'em_andamento'])
                ->count(),
            'past' => Consultation::where('patient_id', $patient->id)
                ->where('status', 'concluida')
                ->count(),
            'cancelled' => Consultation::where('patient_id', $patient->id)
                ->where('status', 'cancelada')
                ->count(),
        ];
        
        return view('portal.consultations', compact('patient', 'consultations', 'stats', 'filter'));
    }

    /**
     * Detalhes da consulta
     */
    public function showConsultation($id)
    {
        $patient = Auth::guard('patient')->user();
        
        $consultation = Consultation::where('patient_id', $patient->id)
            ->where('id', $id)
            ->with(['doctor', 'insurance'])
            ->firstOrFail();

        return view('portal.consultation-show', compact('patient', 'consultation'));
    }

    /**
     * Cancelar consulta
     */
    public function cancelConsultation($id)
    {
        $patient = Auth::guard('patient')->user();

        $consultation = Consultation::where('patient_id', $patient->id)
            ->where('id', $id)
            ->whereIn('status', ['agendada', 'confirmada'])
            ->firstOrFail();

        $consultation->update(['status' => 'cancelada']);

        try {
            PatientActivityLog::log(
                $patient->id,
                'cancelar_consulta',
                "Paciente cancelou consulta agendada para " . $consultation->scheduled_at->format('d/m/Y H:i'),
                ['consultation_id' => $consultation->id]
            );
        } catch (\Exception $e) {
            // Ignorar erro de log
        }

        return back()->with('success', 'Consulta cancelada com sucesso.');
    }

    /**
     * Reenviar credenciais por WhatsApp
     */
    public function resendWhatsApp($consultationId)
    {
        $patient = Auth::guard('patient')->user();
        
        $consultation = Consultation::where('patient_id', $patient->id)
            ->where('id', $consultationId)
            ->where('type', 'teleconsulta')
            ->firstOrFail();

        try {
            $sent = $this->whatsappService->sendConsultationMessage($consultation);
            
            if ($sent) {
                return back()->with('success', '✅ Credenciais reenviadas para seu WhatsApp!');
            } else {
                $waLink = $this->whatsappService->generateWhatsAppLink($consultation);
                return back()->with('whatsapp_manual', $waLink);
            }
        } catch (\Exception $e) {
            $waLink = $this->whatsappService->generateWhatsAppLink($consultation);
            return back()->with('whatsapp_manual', $waLink);
        }
    }

    /**
     * Lista de cotações
     */
    public function quotes()
    {
        $patient = Auth::guard('patient')->user();

        $quotes = Quote::where('patient_id', $patient->id)
            ->with(['items.procedure', 'insurance'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('portal.quotes', compact('patient', 'quotes'));
    }

    /**
     * Detalhes da cotação
     */
    public function showQuote($id)
    {
        $patient = Auth::guard('patient')->user();

        $quote = Quote::where('patient_id', $patient->id)
            ->where('id', $id)
            ->with(['items.procedure', 'insurance'])
            ->firstOrFail();

        return view('portal.quote-show', compact('patient', 'quote'));
    }

    /**
     * Lista de pagamentos
     */
    public function payments()
    {
        $patient = Auth::guard('patient')->user();

        $payments = Payment::where('patient_id', $patient->id)
            ->with(['consultation'])
            ->orderByDesc('created_at')
            ->paginate(10);

        $stats = [
            'total_pago' => Payment::where('patient_id', $patient->id)
                ->where('status', 'confirmado')
                ->sum('amount'),
            'pendente' => Payment::where('patient_id', $patient->id)
                ->where('status', 'pendente')
                ->sum('amount'),
        ];

        return view('portal.payments', compact('patient', 'payments', 'stats'));
    }

    /**
     * Lista de seguradoras
     */
    public function insurances()
    {
        $patient = Auth::guard('patient')->user();

        $insurances = $patient->insurances()
            ->withPivot('policy_number', 'valid_from', 'valid_until', 'is_primary', 'is_active')
            ->get();

        return view('portal.insurances', compact('patient', 'insurances'));
    }

    /**
     * Perfil do paciente
     */
    public function profile()
    {
        $patient = Auth::guard('patient')->user();
        return view('portal.profile', compact('patient'));
    }

    /**
     * Atualizar perfil
     */
    public function updateProfile(Request $request)
    {
        $patient = Auth::guard('patient')->user();

        $validated = $request->validate([
            'phone' => 'nullable|string|size:9|unique:patients,phone,' . $patient->id,
            'address' => 'nullable|string|max:500',
            'medical_history' => 'nullable|string|max:2000',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = [
            'phone' => $validated['phone'] ?? $patient->phone,
            'address' => $validated['address'] ?? $patient->address,
            'medical_history' => $validated['medical_history'] ?? $patient->medical_history,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $patient->update($data);

        try {
            PatientActivityLog::log(
                $patient->id,
                'perfil_atualizado',
                "Paciente atualizou seu perfil",
                []
            );
        } catch (\Exception $e) {
            // Ignorar erro de log
        }

        return back()->with('success', '✅ Perfil atualizado com sucesso!');
    }

    /**
     * Logout do paciente
     */
    public function logout(Request $request)
    {
        $patient = Auth::guard('patient')->user();

        try {
            PatientActivityLog::log(
                $patient->id,
                'logout',
                "Paciente fez logout do portal",
                []
            );
        } catch (\Exception $e) {
            // Ignorar erro de log
        }

        Auth::guard('patient')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('patient.login');
    }
}