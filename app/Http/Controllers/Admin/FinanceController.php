<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FinanceController extends Controller
{
    /**
     * Dashboard Financeiro
     */
    public function dashboard(Request $request)
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

        // Estatísticas
        $totalReceita = Payment::where('status', 'confirmado')
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        $totalPendente = Payment::where('status', 'pendente')
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        $totalCancelado = Payment::where('status', 'cancelado')
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        $totalPagamentos = Payment::where('status', 'confirmado')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // Pagamentos recentes
        $payments = Payment::with(['patient', 'consultation', 'createdBy'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Pagamentos por método (para gráfico)
        $paymentsByMethod = Payment::where('status', 'confirmado')
            ->whereBetween('created_at', [$start, $end])
            ->select('method')
            ->selectRaw('SUM(amount) as total')
            ->groupBy('method')
            ->get();

        // Receita dos últimos 7 dias (para gráfico de linha)
        $last7Days = [];
        $revenue7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last7Days[] = $date->format('d/m');
            $revenue7Days[] = Payment::where('status', 'confirmado')
                ->whereDate('created_at', $date)
                ->sum('amount');
        }

        $stats = [
            'total_receita' => $totalReceita,
            'total_pendente' => $totalPendente,
            'total_cancelado' => $totalCancelado,
            'total_pagamentos' => $totalPagamentos,
            'media_pagamento' => $totalPagamentos > 0 ? $totalReceita / $totalPagamentos : 0,
        ];

        return view('admin.finance.index', compact(
            'stats', 'payments', 'paymentsByMethod',
            'last7Days', 'revenue7Days', 'period',
            'start', 'end'
        ));
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
     * Formulário de criação
     */
    public function createPayment()
    {
        $patients = Patient::where('is_active', true)->orderBy('full_name')->get();
        $consultations = Consultation::whereIn('status', ['concluida', 'em_andamento'])
            ->whereNull('payment_status')
            ->with('patient')
            ->orderByDesc('scheduled_at')
            ->get();
        
        return view('admin.finance.form', compact('patients', 'consultations'));
    }

    /**
     * Salvar pagamento
     */
    public function storePayment(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'consultation_id' => ['nullable', 'exists:consultations,id'],
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

        $payment = Payment::create([
            'patient_id' => $validated['patient_id'],
            'consultation_id' => $validated['consultation_id'] ?? null,
            'amount' => $validated['amount'],
            'method' => $validated['method'],
            'reference' => $validated['reference'] ?? null,
            'description' => $validated['description'] ?? null,
            'paid_at' => $validated['paid_at'] ?? now(),
            'status' => 'confirmado',
            'created_by' => Auth::id(),
        ]);

        // Atualizar status de pagamento da consulta se vinculada
        if ($validated['consultation_id']) {
            Consultation::where('id', $validated['consultation_id'])
                ->update(['payment_status' => 'pago']);
        }

        Log::info('Pagamento registado', [
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