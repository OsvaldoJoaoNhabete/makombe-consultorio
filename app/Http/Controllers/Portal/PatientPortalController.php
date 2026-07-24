<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationRating;
use App\Models\Patient;
use App\Models\Quote;
use App\Models\Payment;
use App\Models\Insurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PatientPortalController extends Controller
{
    /**
     * Helper para obter o paciente associado ao utilizador logado
     */
    private function getPatient()
    {
        $user = Auth::user();
        
        // Associação por email ou telefone (devido ao login unificado)
        $patient = Patient::where('email', $user->email)
                          ->orWhere('phone', $user->phone)
                          ->first();
                          
        return $patient;
    }

    /**
     * Dashboard do Paciente
     */
    public function dashboard()
    {
        $patient = $this->getPatient();

        if (!$patient) {
            return view('portal.dashboard', ['patient' => null, 'upcoming' => collect()]);
        }

        // Próximas consultas agendadas
        $upcoming = Consultation::where('patient_id', $patient->id)
            ->whereIn('status', ['agendada', 'confirmada'])
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at', 'asc')
            ->limit(3)
            ->get();

        return view('portal.dashboard', compact('patient', 'upcoming'));
    }

    /**
     * Formulário de agendamento
     */
    public function showSchedule()
    {
        $patient = $this->getPatient();
        $specialties = \App\Models\Specialty::where('is_active', true)->get();
        $doctors = \App\Models\User::role('Medico')->where('is_active', true)->with('specialty')->get();
        $insurances = Insurance::where('is_active', true)->get();

        return view('portal.schedule', compact('patient', 'specialties', 'doctors', 'insurances'));
    }

    /**
     * Processar novo agendamento
     */
    public function schedule(Request $request)
    {
        $patient = $this->getPatient();
        if (!$patient) {
            return back()->with('error', 'Perfil de paciente não encontrado. Contacte a receção.');
        }

        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:users,id'],
            'specialty_id' => ['nullable', 'exists:specialties,id'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'type' => ['required', 'in:presencial,teleconsulta,domicilio'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Verificar conflito
        $conflict = Consultation::where('doctor_id', $validated['doctor_id'])
            ->where('scheduled_at', $validated['scheduled_at'])
            ->whereNotIn('status', ['cancelada', 'faltou'])
            ->exists();

        if ($conflict) {
            return back()->with('error', 'Este horário já não está disponível. Por favor, escolha outro.');
        }

        $location = null;
        if ($validated['type'] === 'teleconsulta') {
            $location = 'https://meet.jit.si/makombe-' . $patient->id . '-' . time();
        }

        Consultation::create([
            'patient_id' => $patient->id,
            'doctor_id' => $validated['doctor_id'],
            'specialty_id' => $validated['specialty_id'] ?? null,
            'scheduled_at' => $validated['scheduled_at'],
            'type' => $validated['type'],
            'notes' => $validated['notes'] ?? null,
            'location' => $location,
            'status' => 'agendada',
            'payment_status' => 'pendente',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('patient.consultations')
            ->with('success', '✅ Consulta agendada com sucesso! Aguarde a confirmação por SMS/WhatsApp.');
    }

    /**
     * Histórico de Consultas (Sugestão 3)
     */
    public function consultations()
    {
        $patient = $this->getPatient();

        if (!$patient) {
            return redirect()->route('patient.dashboard')->with('error', 'Perfil não encontrado.');
        }

        $consultations = Consultation::where('patient_id', $patient->id)
            ->with(['doctor.specialty', 'rating'])
            ->orderByDesc('scheduled_at')
            ->paginate(10);

        return view('portal.consultations', compact('consultations'));
    }

    /**
     * Detalhes de uma consulta específica
     */
    public function showConsultation($id)
    {
        $patient = $this->getPatient();
        
        $consultation = Consultation::where('id', $id)
            ->where('patient_id', $patient->id)
            ->with(['doctor.specialty', 'insurance', 'rating'])
            ->firstOrFail();

        return view('portal.consultations.show', compact('consultation'));
    }

    /**
     * Cancelar consulta pelo paciente
     */
    public function cancelConsultation($id)
    {
        $patient = $this->getPatient();
        
        $consultation = Consultation::where('id', $id)
            ->where('patient_id', $patient->id)
            ->firstOrFail();

        if (in_array($consultation->status, ['concluida', 'cancelada', 'faltou'])) {
            return back()->with('error', 'Não é possível cancelar esta consulta neste estado.');
        }

        // Regra: só pode cancelar com 24h de antecedência (opcional, mas recomendado)
        // if (Carbon::parse($consultation->scheduled_at)->diffInHours(now()) < 24) { ... }

        $consultation->update(['status' => 'cancelada']);

        return back()->with('success', 'Consulta cancelada com sucesso.');
    }

    /**
     * Reenviar lembrete WhatsApp (Simulação ou integração futura)
     */
    public function resendWhatsApp($id)
    {
        $patient = $this->getPatient();
        $consultation = Consultation::where('id', $id)->where('patient_id', $patient->id)->firstOrFail();
        
        // Aqui entraria a lógica de envio via API WhatsApp
        Log::info('Reenvio de WhatsApp solicitado para consulta #' . $consultation->id);

        return back()->with('success', 'Lembrete reenviado para o seu WhatsApp.');
    }

    /**
     * Formulário de Avaliação (Sugestão 3)
     */
    public function showRateForm($id)
    {
        $patient = $this->getPatient();
        
        $consultation = Consultation::where('id', $id)
            ->where('patient_id', $patient->id)
            ->with(['doctor.specialty'])
            ->firstOrFail();

        // Só pode avaliar se estiver concluída
        if ($consultation->status !== 'concluida') {
            return redirect()->route('patient.consultations')->with('error', 'Só pode avaliar consultas concluídas.');
        }

        // Verificar se já avaliou
        $existingRating = ConsultationRating::where('consultation_id', $id)
                                            ->where('patient_id', $patient->id)
                                            ->first();

        if ($existingRating) {
            return redirect()->route('patient.consultations')->with('info', 'Você já avaliou esta consulta.');
        }

        return view('portal.consultations.rate', compact('consultation'));
    }

    /**
     * Processar a Avaliação (Sugestão 3)
     */
    public function rateConsultation(Request $request, $id)
    {
        $patient = $this->getPatient();
        
        $consultation = Consultation::where('id', $id)
            ->where('patient_id', $patient->id)
            ->firstOrFail();

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        // Segurança contra duplicação
        $exists = ConsultationRating::where('consultation_id', $id)
                                    ->where('patient_id', $patient->id)
                                    ->exists();

        if ($exists) {
            return back()->with('error', 'Avaliação já registada.');
        }

        ConsultationRating::create([
            'consultation_id' => $consultation->id,
            'patient_id' => $patient->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->route('patient.consultations')
            ->with('success', '✅ Obrigado pela sua avaliação! A sua opinião ajuda-nos a melhorar.');
    }

    /**
     * Cotações do Paciente
     */
    public function quotes()
    {
        $patient = $this->getPatient();
        $quotes = Quote::where('patient_id', $patient->id)->orderByDesc('created_at')->paginate(10);
        return view('portal.quotes', compact('quotes'));
    }

    public function showQuote($id)
    {
        $patient = $this->getPatient();
        $quote = Quote::where('id', $id)->where('patient_id', $patient->id)->with('items')->firstOrFail();
        return view('portal.quotes.show', compact('quote'));
    }

    /**
     * Pagamentos do Paciente
     */
    public function payments()
    {
        $patient = $this->getPatient();
        // Buscar pagamentos associados às consultas do paciente
        $payments = Payment::whereHas('consultation', function($q) use ($patient) {
            $q->where('patient_id', $patient->id);
        })->orderByDesc('created_at')->paginate(10);
        
        return view('portal.payments', compact('payments'));
    }

    /**
     * Seguradoras do Paciente
     */
    public function insurances()
    {
        $patient = $this->getPatient();
        $insurances = $patient->insurances; // Relação many-to-many
        return view('portal.insurances', compact('insurances'));
    }

    /**
     * Perfil do Paciente
     */
    public function profile()
    {
        $patient = $this->getPatient();
        return view('portal.profile', compact('patient'));
    }

    public function updateProfile(Request $request)
    {
        $patient = $this->getPatient();
        
        $validated = $request->validate([
            'address' => ['nullable', 'string', 'max:500'],
            'medical_history' => ['nullable', 'string', 'max:2000'],
            'emergency_contact_name' => ['nullable', 'string', 'max:150'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
        ]);

        $patient->update($validated);

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }
}