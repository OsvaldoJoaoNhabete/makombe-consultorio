<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function dashboard()
    {
        $payments = Payment::with(['patient'])->orderByDesc('created_at')->paginate(15);
        return view('admin.finance.index', compact('payments'));
    }

    public function reports()
    {
        return view('admin.finance.reports');
    }

    public function payments()
    {
        $payments = Payment::with(['patient'])->orderByDesc('created_at')->paginate(15);
        return view('admin.finance.payments', compact('payments'));
    }

    public function createPayment()
    {
        return view('admin.finance.form');
    }

    public function storePayment(Request $request)
    {
        return back()->with('info', 'Funcionalidade em desenvolvimento.');
    }

    public function showPayment($id)
    {
        $payment = Payment::with(['patient'])->findOrFail($id);
        return view('admin.finance.show', compact('payment'));
    }

    public function confirmPayment($id)
    {
        Payment::findOrFail($id)->update(['status' => 'confirmado', 'paid_at' => now()]);
        return back()->with('success', 'Pagamento confirmado.');
    }

    public function cancelPayment($id)
    {
        Payment::findOrFail($id)->update(['status' => 'cancelado']);
        return back()->with('success', 'Pagamento cancelado.');
    }
}