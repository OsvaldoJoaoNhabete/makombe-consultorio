<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Payment;
use App\Models\User;
use App\Models\Quote;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ManagerialReportsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $reportType = $request->input('type', 'dashboard');
        
        // Definir datas
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            $label = $start->format('d/m/Y') . ' a ' . $end->format('d/m/Y');
        } else {
            switch ($period) {
                case 'today':
                    $start = Carbon::now()->startOfDay();
                    $end = Carbon::now()->endOfDay();
                    $label = 'Hoje (' . $start->format('d/m/Y') . ')';
                    break;
                case 'yesterday':
                    $start = Carbon::yesterday();
                    $end = Carbon::yesterday();
                    $label = 'Ontem (' . $start->format('d/m/Y') . ')';
                    break;
                case 'week':
                    $start = Carbon::now()->startOfWeek();
                    $end = Carbon::now()->endOfWeek();
                    $label = 'Esta Semana';
                    break;
                case 'last_week':
                    $start = Carbon::now()->subWeek()->startOfWeek();
                    $end = Carbon::now()->subWeek()->endOfWeek();
                    $label = 'Semana Passada';
                    break;
                case 'month':
                    $start = Carbon::now()->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                    $label = 'Este Mês (' . $start->format('m/Y') . ')';
                    break;
                case 'last_month':
                    $start = Carbon::now()->subMonth()->startOfMonth();
                    $end = Carbon::now()->subMonth()->endOfMonth();
                    $label = 'Mês Passado (' . $start->format('m/Y') . ')';
                    break;
                case 'year':
                    $start = Carbon::now()->startOfYear();
                    $end = Carbon::now()->endOfYear();
                    $label = 'Este Ano (' . $start->format('Y') . ')';
                    break;
                case 'last_year':
                    $start = Carbon::now()->subYear()->startOfYear();
                    $end = Carbon::now()->subYear()->endOfYear();
                    $label = 'Ano Passado (' . $start->format('Y') . ')';
                    break;
                default:
                    $start = Carbon::now()->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                    $label = 'Este Mês';
            }
        }

        // Estatísticas Gerais
        $stats = [
            'total_revenue' => Payment::where('status', 'confirmado')->sum('amount'),
            'period_revenue' => Payment::where('status', 'confirmado')->whereBetween('created_at', [$start, $end])->sum('amount'),
            'total_consultations' => Consultation::count(),
            'period_consultations' => Consultation::whereBetween('scheduled_at', [$start, $end])->count(),
            'total_patients' => Patient::count(),
            'period_new_patients' => Patient::whereBetween('created_at', [$start, $end])->count(),
            'total_users' => User::role(['Medico', 'Enfermeiro', 'Atendente', 'Gerente', 'Administrador'])->count(),
            'pending_payments' => Payment::where('status', 'pendente')->sum('amount'),
            'period_pending' => Payment::where('status', 'pendente')->whereBetween('created_at', [$start, $end])->sum('amount'),
        ];

        $data = [];
        
        switch ($reportType) {
            case 'consultations':
                $data['consultations'] = Consultation::with(['patient', 'doctor', 'insurance'])
                    ->whereBetween('scheduled_at', [$start, $end])
                    ->orderByDesc('scheduled_at')
                    ->get();
                
                $cType = Consultation::selectRaw('type, COUNT(*) as count')
                    ->whereBetween('scheduled_at', [$start, $end])
                    ->groupBy('type')
                    ->get();
                $data['consultTypeLabels'] = $cType->pluck('type')->toArray();
                $data['consultTypeData'] = $cType->pluck('count')->toArray();
                
                $cStatus = Consultation::selectRaw('status, COUNT(*) as count')
                    ->whereBetween('scheduled_at', [$start, $end])
                    ->groupBy('status')
                    ->get();
                $data['consultStatusLabels'] = $cStatus->pluck('status')->toArray();
                $data['consultStatusData'] = $cStatus->pluck('count')->toArray();
                
                $data['topDoctors'] = Consultation::with('doctor')
                    ->selectRaw('doctor_id, COUNT(*) as count')
                    ->whereBetween('scheduled_at', [$start, $end])
                    ->groupBy('doctor_id')
                    ->orderByDesc('count')
                    ->limit(5)
                    ->get();
                break;

            case 'patients':
                $data['patients'] = Patient::whereBetween('created_at', [$start, $end])
                    ->orderByDesc('created_at')
                    ->get();
                
                $pGender = Patient::selectRaw('gender, COUNT(*) as count')
                    ->whereBetween('created_at', [$start, $end])
                    ->groupBy('gender')
                    ->get();
                $data['patientsGenderLabels'] = $pGender->pluck('gender')->toArray();
                $data['patientsGenderData'] = $pGender->pluck('count')->toArray();
                
                $data['topPatients'] = Patient::withCount('consultations')
                    ->orderByDesc('consultations_count')
                    ->limit(10)
                    ->get();
                break;

            case 'payments':
                $data['payments'] = Payment::with(['patient', 'consultation.doctor'])
                    ->whereBetween('created_at', [$start, $end])
                    ->orderByDesc('created_at')
                    ->get();
                
                $pMethod = Payment::selectRaw('method, COUNT(*) as count, SUM(amount) as total')
                    ->whereBetween('created_at', [$start, $end])
                    ->groupBy('method')
                    ->get();
                $data['paymentMethodLabels'] = $pMethod->pluck('method')->toArray();
                $data['paymentMethodData'] = $pMethod->pluck('total')->toArray();
                break;

            case 'financial':
                $dRevenue = Payment::selectRaw('DATE(created_at) as date, SUM(amount) as total')
                    ->whereBetween('created_at', [$start, $end])
                    ->where('status', 'confirmado')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                $data['dailyRevenueLabels'] = $dRevenue->pluck('date')->toArray();
                $data['dailyRevenueData'] = $dRevenue->pluck('total')->toArray();
                
                // CORREÇÃO: Usar Consultation em vez de Payment, pois Payment não tem insurance_id
                $data['insuranceRevenue'] = Consultation::with('insurance')
                    ->whereNotNull('insurance_id')
                    ->whereBetween('scheduled_at', [$start, $end])
                    ->selectRaw('insurance_id, SUM(total_amount) as total, COUNT(*) as count')
                    ->groupBy('insurance_id')
                    ->get();
                break;

            case 'users':
                $users = User::role(['Medico', 'Enfermeiro', 'Atendente', 'Gerente', 'Administrador'])
                    ->orderByDesc('created_at')
                    ->get();
                
                // CORREÇÃO: Contar consultas manualmente para evitar erro de relacionamento inexistente no Model User
                foreach ($users as $user) {
                    $user->consultations_count = Consultation::where('doctor_id', $user->id)->count();
                }
                $data['users'] = $users;
                break;

            case 'quotes':
                $data['quotes'] = Quote::with(['patient', 'insurance'])
                    ->whereBetween('created_at', [$start, $end])
                    ->orderByDesc('created_at')
                    ->get();
                break;

            default: // dashboard
                $data['dailyLabels'] = [];
                $data['dailyRevenue'] = [];
                $data['dailyConsultations'] = [];
                
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $data['dailyLabels'][] = $date->format('d/m');
                    $data['dailyRevenue'][] = Payment::where('status', 'confirmado')
                        ->whereDate('created_at', $date)
                        ->sum('amount');
                    $data['dailyConsultations'][] = Consultation::whereDate('scheduled_at', $date)->count();
                }
                
                $data['topDoctors'] = Consultation::with('doctor')
                    ->selectRaw('doctor_id, COUNT(*) as count')
                    ->whereBetween('scheduled_at', [$start, $end])
                    ->groupBy('doctor_id')
                    ->orderByDesc('count')
                    ->limit(5)
                    ->get();
                
                $data['recentConsultations'] = Consultation::with(['patient', 'doctor'])
                    ->whereBetween('scheduled_at', [$start, $end])
                    ->orderByDesc('scheduled_at')
                    ->limit(10)
                    ->get();
        }

        return view('admin.reports.index', compact(
            'stats', 'period', 'label', 'start', 'end', 'reportType', 'data', 'startDate', 'endDate'
        ));
    }
}