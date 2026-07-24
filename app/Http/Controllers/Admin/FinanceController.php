<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FinanceController extends Controller
{
    /**
     * Dashboard Financeiro Geral
     * Sugestão 1: Apenas Gerente, Financeiro, Contabilista, Administrador, Proprietária podem ver
     */
    public function dashboard(Request $request)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Receita total do mês (todas as consultas concluídas)
        $monthlyRevenue = Consultation::where('status', 'concluida')
            ->whereMonth('scheduled_at', $currentMonth)
            ->whereYear('scheduled_at', $currentYear)
            ->sum('total_amount');

        // Receita acumulada (histórico total)
        $accumulatedRevenue = Consultation::where('status', 'concluida')
            ->sum('total_amount');

        // Total de consultas do mês
        $monthlyConsultations = Consultation::whereMonth('scheduled_at', $currentMonth)
            ->whereYear('scheduled_at', $currentYear)
            ->count();

        // Consultas concluídas do mês
        $completedConsultations = Consultation::where('status', 'concluida')
            ->whereMonth('scheduled_at', $currentMonth)
            ->whereYear('scheduled_at', $currentYear)
            ->count();

        // Receita por médico (ranking do mês)
        $revenueByDoctor = Consultation::where('status', 'concluida')
            ->whereMonth('scheduled_at', $currentMonth)
            ->whereYear('scheduled_at', $currentYear)
            ->select('doctor_id', DB::raw('SUM(total_amount) as total'))
            ->groupBy('doctor_id')
            ->orderByDesc('total')
            ->with('doctor')
            ->limit(10)
            ->get();

        // Pagamentos pendentes
        $pendingPayments = Consultation::where('payment_status', 'pendente')
            ->where('status', 'concluida')
            ->count();

        $stats = [
            'monthly_revenue' => $monthlyRevenue,
            'accumulated_revenue' => $accumulatedRevenue,
            'monthly_consultations' => $monthlyConsultations,
            'completed_consultations' => $completedConsultations,
            'pending_payments' => $pendingPayments,
        ];

        return view('admin.finance.dashboard', compact('stats', 'revenueByDoctor', 'currentMonth', 'currentYear'));
    }


    /**
     * Lista de pagamentos
     */
    public function payments(Request $request)
    {
        $status = $request->input('status', 'all');
        $method = $request->input('method', 'all');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = Payment::with(['patient', 'consultation', 'createdBy']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($method !== 'all') {
            $query->where('method', $method);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $payments = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $stats = [
            'total' => Payment::count(),
            'confirmados' => Payment::where('status', 'confirmado')->count(),
            'pendentes' => Payment::where('status', 'pendente')->count(),
            'cancelados' => Payment::where('status', 'cancelado')->count(),
        ];

        return view('admin.finance.payments', compact('payments', 'stats', 'status', 'method', 'dateFrom', 'dateTo'));
    }

     /**
     * Formulário de criação de pagamento
     */
    public function createPayment()
    {
        $patients = \App\Models\Patient::where('is_active', true)
            ->orderBy('full_name')
            ->get();

        // Buscar consultas com pagamento pendente ou parcial para facilitar o vínculo
        $pendingConsultations = \App\Models\Consultation::whereIn('payment_status', ['pendente', 'parcial'])
            ->with(['patient', 'doctor'])
            ->orderByDesc('scheduled_at')
            ->get();

        return view('admin.finance.form', compact('patients', 'pendingConsultations'));
    }

    /**
     * Salvar pagamento
     */
    public function storePayment(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'consultation_id' => ['nullable', 'exists:consultations,id'],
            'quote_id' => ['nullable', 'exists:quotes,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'method' => ['required', 'in:mpesa,emola,transferencia,numerario,cheque,cartao,seguradora'],
            'reference' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'paid_at' => ['nullable', 'date'],
        ], [
            'patient_id.required' => 'Selecione um paciente.',
            'amount.required' => 'O valor é obrigatório.',
            'amount.min' => 'O valor deve ser maior que zero.',
            'method.required' => 'Selecione o método de pagamento.',
        ]);

        $payment = \App\Models\Payment::create([
            'patient_id' => $validated['patient_id'],
            'consultation_id' => $validated['consultation_id'] ?? null,
            'quote_id' => $validated['quote_id'] ?? null,
            'amount' => $validated['amount'],
            'method' => $validated['method'],
            'reference' => $validated['reference'] ?? null,
            'description' => $validated['description'] ?? null,
            'paid_at' => $validated['paid_at'] ?? now(),
            'status' => 'confirmado',
            'created_by' => Auth::id(),
        ]);

        // Atualizar status de pagamento da consulta se vinculada
        if (!empty($validated['consultation_id'])) {
            \App\Models\Consultation::where('id', $validated['consultation_id'])
                ->update(['payment_status' => 'pago']);
        }

        \Log::info('Pagamento registado', [
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'patient_id' => $payment->patient_id,
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('financeiro.payments.index')
            ->with('success', "✅ Pagamento de " . number_format($payment->amount, 2, ',', '.') . " MT registado com sucesso!");
    }

    /**
     * Detalhes do pagamento
     */
    public function showPayment($id)
    {
        $payment = Payment::with(['patient', 'consultation.doctor', 'createdBy'])->findOrFail($id);
        return view('admin.finance.show', compact('payment'));
    }

    /**
     * Confirmar pagamento
     */
    public function confirmPayment($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => 'confirmado', 'paid_at' => now()]);

        if ($payment->consultation_id) {
            Consultation::where('id', $payment->consultation_id)
                ->update(['payment_status' => 'pago']);
        }

        return back()->with('success', '✅ Pagamento confirmado!');
    }

    /**
     * Cancelar pagamento
     */
    public function cancelPayment($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => 'cancelado']);

        if ($payment->consultation_id) {
            Consultation::where('id', $payment->consultation_id)
                ->update(['payment_status' => 'pendente']);
        }

        return back()->with('success', 'Pagamento cancelado.');
    }

    /**
     * Relatórios
     */
    public function reports(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $payments = Payment::where('status', 'confirmado')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->with(['patient'])
            ->get();

        $totalReceita = $payments->sum('amount');
        $totalPagamentos = $payments->count();
        $mediaPagamento = $totalPagamentos > 0 ? $totalReceita / $totalPagamentos : 0;

        // Por método
        $byMethod = $payments->groupBy('method')->map(function($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amount'),
            ];
        });

        // Por dia
        $byDay = $payments->groupBy(function($payment) {
            return $payment->created_at->format('Y-m-d');
        })->map(function($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amount'),
            ];
        })->sortKeys();

        return view('admin.finance.reports', compact(
            'payments', 'totalReceita', 'totalPagamentos', 'mediaPagamento',
            'byMethod', 'byDay', 'dateFrom', 'dateTo'
        ));
    }
}