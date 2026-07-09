<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Payment;
use App\Models\Insurance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManagerialReportsController extends Controller
{
    /**
     * Dashboard de Relatórios Gerenciais
     */
    public function index(Request $request)
    {
        $period = $request->input('period', 'month');

        // Definir período
        switch ($period) {
            case 'today':
                $start = Carbon::today();
                $end = Carbon::today()->endOfDay();
                break;
            case 'week':
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
                break;
            case 'year':
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
                break;
            default: // month
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
        }

        // ============================================
        // ESTATÍSTICAS GERAIS
        // ============================================
        $totalPatients = Patient::count();
        $totalConsultations = Consultation::count();
        $totalRevenue = Payment::where('status', 'confirmado')->sum('amount');
        $monthRevenue = Payment::where('status', 'confirmado')
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('amount');

        // ============================================
        // CONSULTAS POR TIPO
        // ============================================
        $consultationsByType = Consultation::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        // ============================================
        // CONSULTAS POR STATUS
        // ============================================
        $consultationsByStatus = Consultation::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // ============================================
        // TOP 5 MÉDICOS
        // ============================================
        $topDoctors = Consultation::selectRaw('doctor_id, COUNT(*) as count')
            ->with('doctor:id,name')
            ->groupBy('doctor_id')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // ============================================
        // TOP 5 SEGURADORAS
        // ============================================
        $topInsurances = Consultation::whereNotNull('insurance_id')
            ->selectRaw('insurance_id, COUNT(*) as count')
            ->with('insurance:id,name')
            ->groupBy('insurance_id')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // ============================================
        // RECEITA DOS ÚLTIMOS 6 MESES
        // ============================================
        $monthlyRevenue = [];
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyLabels[] = $date->format('M/Y');
            $monthlyRevenue[] = Payment::where('status', 'confirmado')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');
        }

        // ============================================
        // RECEITA DOS ÚLTIMOS 7 DIAS
        // ============================================
        $dailyRevenue = [];
        $dailyLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyLabels[] = $date->format('d/m');
            $dailyRevenue[] = Payment::where('status', 'confirmado')
                ->whereDate('created_at', $date)
                ->sum('amount');
        }

        // ============================================
        // PACIENTES NOVOS POR MÊS
        // ============================================
        $newPatientsByMonth = [];
        $newPatientsLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $newPatientsLabels[] = $date->format('M/Y');
            $newPatientsByMonth[] = Patient::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        // ============================================
        // CONSULTAS POR DIA DA SEMANA
        // ============================================
        $consultationsByWeekday = [];
        $weekdayLabels = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
        for ($i = 0; $i < 7; $i++) {
            $consultationsByWeekday[] = Consultation::whereRaw('DAYOFWEEK(scheduled_at) = ?', [$i + 1])
                ->count();
        }

        return view('admin.reports.index', compact(
            'period',
            'totalPatients',
            'totalConsultations',
            'totalRevenue',
            'monthRevenue',
            'consultationsByType',
            'consultationsByStatus',
            'topDoctors',
            'topInsurances',
            'monthlyLabels',
            'monthlyRevenue',
            'dailyLabels',
            'dailyRevenue',
            'newPatientsLabels',
            'newPatientsByMonth',
            'weekdayLabels',
            'consultationsByWeekday'
        ));
    }
}