<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Patient;
use App\Models\Insurance;
use App\Models\Procedure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class QuoteManageController extends Controller
{
    /**
     * Lista de cotações
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $search = $request->input('search');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = Quote::with(['patient', 'insurance', 'items', 'createdBy']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('nid', 'LIKE', "%{$search}%");
            });
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $quotes = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $stats = [
            'total' => Quote::count(),
            'rascunhos' => Quote::where('status', 'rascunho')->count(),
            'enviadas' => Quote::where('status', 'enviada')->count(),
            'aprovadas' => Quote::where('status', 'aprovada')->count(),
            'recusadas' => Quote::where('status', 'recusada')->count(),
            'valor_total' => Quote::where('status', 'aprovada')->sum('final_amount'),
        ];

        return view('admin.quotes.index', compact('quotes', 'stats', 'status', 'search', 'dateFrom', 'dateTo'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        $patients = Patient::where('is_active', true)->orderBy('full_name')->get();
        $insurances = Insurance::where('is_active', true)->orderBy('name')->get();
        $procedures = Procedure::where('is_active', true)->orderBy('category')->orderBy('name')->get();
        
        // Agrupar procedimentos por categoria
        $proceduresByCategory = $procedures->groupBy('category');

        return view('admin.quotes.form', compact('patients', 'insurances', 'proceduresByCategory'));
    }

    /**
     * Salvar nova cotação
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'insurance_id' => ['nullable', 'exists:insurances,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'valid_until' => ['nullable', 'date', 'after:today'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'discount_type' => ['nullable', 'in:percentage,fixed'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.procedure_id' => ['nullable', 'exists:procedures,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ], [
            'patient_id.required' => 'Selecione um paciente.',
            'items.required' => 'Adicione pelo menos um item à cotação.',
            'items.min' => 'Adicione pelo menos um item à cotação.',
        ]);

        $quote = Quote::create([
            'patient_id' => $validated['patient_id'],
            'insurance_id' => $validated['insurance_id'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'valid_until' => $validated['valid_until'] ?? now()->addDays(15),
            'discount' => $validated['discount'] ?? 0,
            'discount_type' => $validated['discount_type'] ?? 'fixed',
            'status' => 'rascunho',
            'created_by' => Auth::id(),
        ]);

        // Adicionar itens
        foreach ($validated['items'] as $item) {
            $totalPrice = $item['quantity'] * $item['unit_price'];
            
            QuoteItem::create([
                'quote_id' => $quote->id,
                'procedure_id' => $item['procedure_id'] ?? null,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $totalPrice,
            ]);
        }

        // Calcular totais
        $quote->calculateTotal();

        Log::info('Cotação criada', [
            'quote_id' => $quote->id,
            'patient_id' => $quote->patient_id,
            'total' => $quote->final_amount,
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('quotes.show', $quote->id)
            ->with('success', '✅ Cotação criada com sucesso!');
    }

    /**
     * Detalhes da cotação
     */
    public function show($id)
    {
        $quote = Quote::with(['patient.insurances', 'insurance', 'items.procedure', 'createdBy'])
            ->findOrFail($id);

        return view('admin.quotes.show', compact('quote'));
    }

    /**
     * Formulário de edição
     */
    public function edit($id)
    {
        $quote = Quote::with('items')->findOrFail($id);
        
        if (!in_array($quote->status, ['rascunho', 'enviada'])) {
            return redirect()
                ->route('quotes.show', $quote->id)
                ->with('error', 'Não é possível editar uma cotação com status ' . $quote->getStatusLabel());
        }

        $patients = Patient::where('is_active', true)->orderBy('full_name')->get();
        $insurances = Insurance::where('is_active', true)->orderBy('name')->get();
        $procedures = Procedure::where('is_active', true)->orderBy('category')->orderBy('name')->get();
        $proceduresByCategory = $procedures->groupBy('category');

        return view('admin.quotes.form', compact('quote', 'patients', 'insurances', 'proceduresByCategory'));
    }

    /**
     * Atualizar cotação
     */
    public function update(Request $request, $id)
    {
        $quote = Quote::findOrFail($id);

        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'insurance_id' => ['nullable', 'exists:insurances,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'valid_until' => ['nullable', 'date'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'discount_type' => ['nullable', 'in:percentage,fixed'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.procedure_id' => ['nullable', 'exists:procedures,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        $quote->update([
            'patient_id' => $validated['patient_id'],
            'insurance_id' => $validated['insurance_id'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'valid_until' => $validated['valid_until'] ?? $quote->valid_until,
            'discount' => $validated['discount'] ?? 0,
            'discount_type' => $validated['discount_type'] ?? 'fixed',
        ]);

        // Remover itens antigos
        $quote->items()->delete();

        // Adicionar novos itens
        foreach ($validated['items'] as $item) {
            QuoteItem::create([
                'quote_id' => $quote->id,
                'procedure_id' => $item['procedure_id'] ?? null,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        $quote->calculateTotal();

        return redirect()
            ->route('quotes.show', $quote->id)
            ->with('success', '✅ Cotação atualizada!');
    }

    /**
     * Enviar cotação ao paciente
     */
    public function send($id)
    {
        $quote = Quote::findOrFail($id);
        
        if (!in_array($quote->status, ['rascunho'])) {
            return back()->with('error', 'Esta cotação não pode ser enviada.');
        }

        $quote->update([
            'status' => 'enviada',
            'sent_at' => now(),
        ]);

        return back()->with('success', '✅ Cotação enviada ao paciente!');
    }

    /**
     * Aprovar cotação
     */
    public function approve($id)
    {
        $quote = Quote::findOrFail($id);
        
        if (!in_array($quote->status, ['enviada', 'rascunho'])) {
            return back()->with('error', 'Esta cotação não pode ser aprovada.');
        }

        $quote->update([
            'status' => 'aprovada',
            'approved_at' => now(),
        ]);

        return back()->with('success', '✅ Cotação aprovada!');
    }

    /**
     * Recusar cotação
     */
    public function reject($id)
    {
        $quote = Quote::findOrFail($id);
        
        if (!in_array($quote->status, ['enviada', 'rascunho'])) {
            return back()->with('error', 'Esta cotação não pode ser recusada.');
        }

        $quote->update([
            'status' => 'recusada',
            'rejected_at' => now(),
        ]);

        return back()->with('success', 'Cotação recusada.');
    }

    /**
     * Marcar como paga
     */
    public function markAsPaid($id)
    {
        $quote = Quote::findOrFail($id);
        
        if ($quote->status !== 'aprovada') {
            return back()->with('error', 'Apenas cotações aprovadas podem ser marcadas como pagas.');
        }

        $quote->update(['status' => 'paga']);

        return back()->with('success', '✅ Cotação marcada como paga!');
    }

    /**
     * Excluir cotação
     */
    public function destroy($id)
    {
        $quote = Quote::findOrFail($id);
        
        if (!in_array($quote->status, ['rascunho'])) {
            return back()->with('error', 'Apenas cotações em rascunho podem ser excluídas.');
        }
        
        $quote->delete();

        return redirect()
            ->route('quotes.index')
            ->with('success', '✅ Cotação excluída.');
    }

    /**
     * Converter cotação em consulta
     */
    public function convertToConsultation($id)
    {
        $quote = Quote::with('items')->findOrFail($id);
        
        if ($quote->status !== 'aprovada') {
            return back()->with('error', 'Apenas cotações aprovadas podem ser convertidas em consulta.');
        }

        // Criar consulta baseada na cotação
        $consultation = \App\Models\Consultation::create([
            'patient_id' => $quote->patient_id,
            'doctor_id' => Auth::id(), // O utilizador que converteu
            'scheduled_at' => now()->addDays(1),
            'type' => 'presencial',
            'insurance_id' => $quote->insurance_id,
            'total_amount' => $quote->final_amount,
            'notes' => "Gerada a partir da Cotação #{$quote->id}",
            'status' => 'agendada',
            'payment_status' => 'pendente',
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('consultations.show', $consultation->id)
            ->with('success', '✅ Consulta criada a partir da cotação!');
    }
}