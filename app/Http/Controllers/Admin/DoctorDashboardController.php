<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoctorDashboardController extends Controller
{
    /**
     * Dashboard Individual do Médico/Enfermeiro
     * Sugestão 1: Cada médico vê apenas as suas próprias estatísticas
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Sugestão 9: Médico só vê pacientes que atendeu
        $myPatients = Patient::whereHas('consultations', function ($query) use ($user) {
            $query->where('doctor_id', $user->id);
        })->count();

        // Total de consultas do médico (todas)
        $totalConsultations = Consultation::where('doctor_id', $user->id)->count();

        // Consultas do mês atual
        $monthlyConsultations = Consultation::where('doctor_id', $user->id)
            ->whereMonth('scheduled_at', $currentMonth)
            ->whereYear('scheduled_at', $currentYear)
            ->count();

        // Receita do mês atual (Sugestão 1: Receita individual mensal)
        $monthlyRevenue = Consultation::where('doctor_id', $user->id)
            ->whereMonth('scheduled_at', $currentMonth)
            ->whereYear('scheduled_at', $currentYear)
            ->where('status', 'concluida')
            ->sum('total_amount');

        // Receita acumulada (total histórico)
        $accumulatedRevenue = Consultation::where('doctor_id', $user->id)
            ->where('status', 'concluida')
            ->sum('total_amount');

        // Consultas de hoje
        $todayConsultations = Consultation::where('doctor_id', $user->id)
            ->whereDate('scheduled_at', today())
            ->orderBy('scheduled_at', 'asc')
            ->get();

        // Próximas consultas (próximos 7 dias)
        $upcomingConsultations = Consultation::where('doctor_id', $user->id)
            ->where('scheduled_at', '>=', now())
            ->where('scheduled_at', '<=', now()->addDays(7))
            ->whereNotIn('status', ['cancelada', 'faltou', 'concluida'])
            ->orderBy('scheduled_at', 'asc')
            ->with(['patient', 'specialty'])
            ->limit(10)
            ->get();

        // Estatísticas por status do mês atual
        $statusStats = Consultation::where('doctor_id', $user->id)
            ->whereMonth('scheduled_at', $currentMonth)
            ->whereYear('scheduled_at', $currentYear)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $stats = [
            'my_patients' => $myPatients,
            'total_consultations' => $totalConsultations,
            'monthly_consultations' => $monthlyConsultations,
            'monthly_revenue' => $monthlyRevenue,
            'accumulated_revenue' => $accumulatedRevenue,
            'today_consultations' => $todayConsultations->count(),
            'agendadas' => $statusStats['agendada'] ?? 0,
            'confirmadas' => $statusStats['confirmada'] ?? 0,
            'concluidas' => $statusStats['concluida'] ?? 0,
            'canceladas' => $statusStats['cancelada'] ?? 0,
        ];

        return view('admin.doctor.dashboard', compact(
            'stats',
            'todayConsultations',
            'upcomingConsultations',
            'currentMonth',
            'currentYear'
        ));
    }

    /**
     * Detalhes de uma consulta específica do médico
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $consultation = Consultation::with(['patient', 'doctor.specialty', 'insurance'])
            ->where('doctor_id', $user->id)
            ->findOrFail($id);

        return view('admin.doctor.consultation-show', compact('consultation'));
    }

    /**
     * Atender consulta (iniciar atendimento)
     */
    public function attend($id)
    {
        $user = Auth::user();
        $consultation = Consultation::where('doctor_id', $user->id)->findOrFail($id);
        
        return view('admin.doctor.attend', compact('consultation'));
    }

    /**
     * Guardar dados do atendimento (prescrição, exames, diagnóstico)
     */
    public function storeAttendance(Request $request, $id)
    {
        $user = Auth::user();
        $consultation = Consultation::where('doctor_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'prescription' => ['nullable', 'string', 'max:5000'],
            'clinical_notes' => ['nullable', 'string', 'max:5000'],
            'diagnosis' => ['nullable', 'string', 'max:2000'],
            'observations' => ['nullable', 'string', 'max:2000'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $consultation->update([
            'prescription' => $validated['prescription'] ?? null,
            'clinical_notes' => $validated['clinical_notes'] ?? null,
            'diagnosis' => $validated['diagnosis'] ?? null,
            'observations' => $validated['observations'] ?? null,
            'total_amount' => $validated['total_amount'] ?? $consultation->total_amount,
            'status' => 'concluida',
        ]);

        return redirect()
            ->route('doctor.index')
            ->with('success', '✅ Atendimento registado e consulta concluída!');
    }

    /**
     * Concluir consulta
     */
    public function complete($id)
    {
        $user = Auth::user();
        $consultation = Consultation::where('doctor_id', $user->id)->findOrFail($id);
        
        $consultation->update(['status' => 'concluida']);

        return back()->with('success', '✅ Consulta marcada como concluída!');
    }

    /**
     * Cancelar consulta
     */
    public function cancel($id)
    {
        $user = Auth::user();
        $consultation = Consultation::where('doctor_id', $user->id)->findOrFail($id);
        
        $consultation->update(['status' => 'cancelada']);

        return back()->with('success', '✅ Consulta cancelada.');
    }

    /**
     * Iniciar videochamada (Sugestão 11: Link direto Jitsi)
     */
    public function startVideoCall($id)
    {
        $user = Auth::user();
        $consultation = Consultation::where('doctor_id', $user->id)->findOrFail($id);

        // Se não tiver link, gerar um
        if (!$consultation->location) {
            $roomName = 'makombe-' . $consultation->id . '-' . time();
            $consultation->update(['location' => 'https://meet.jit.si/' . $roomName]);
        }

        return redirect()->away($consultation->location);
    }
}