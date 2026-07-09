<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Payment;
use App\Models\PatientActivityLog;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        // Estatísticas gerais
        $stats = [
            'total_patients' => Patient::count(),
            'active_patients' => Patient::where('is_active', true)->count(),
            'total_consultations' => Consultation::count(),
            'today_consultations' => Consultation::whereDate('scheduled_at', $today)->count(),
            'week_consultations' => Consultation::whereBetween('scheduled_at', [$weekStart, $weekEnd])->count(),
            'month_consultations' => Consultation::whereBetween('scheduled_at', [$monthStart, $monthEnd])->count(),
            'today_revenue' => Payment::whereDate('created_at', $today)
                ->where('status', 'confirmado')
                ->sum('amount'),
            'week_revenue' => Payment::whereBetween('created_at', [$weekStart, $weekEnd])
                ->where('status', 'confirmado')
                ->sum('amount'),
            'month_revenue' => Payment::whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('status', 'confirmado')
                ->sum('amount'),
        ];

        // Consultas de hoje
        $todayConsultations = Consultation::whereDate('scheduled_at', $today)
            ->whereNotIn('status', ['cancelada', 'concluida'])
            ->with(['patient', 'doctor'])
            ->orderBy('scheduled_at')
            ->limit(8)
            ->get();

        // Atividades recentes
        $recentActivities = PatientActivityLog::with(['patient', 'user'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Dados para gráfico (últimos 7 dias)
        $chartLabels = [];
        $chartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('d/m');
            $chartData[] = Consultation::whereDate('scheduled_at', $date)->count();
        }

        return view('admin.dashboard', compact(
            'stats',
            'todayConsultations',
            'recentActivities',
            'chartLabels',
            'chartData'
        ));
    }
}