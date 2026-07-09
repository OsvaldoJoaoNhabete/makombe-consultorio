<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Insurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InsuranceManageController extends Controller
{
    /**
     * Lista de seguradoras
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', 'all');

        $query = Insurance::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $insurances = $query->orderBy('name')->paginate(15)->withQueryString();

        $stats = [
            'total' => Insurance::count(),
            'ativas' => Insurance::where('is_active', true)->count(),
            'inativas' => Insurance::where('is_active', false)->count(),
            'cobertura_media' => Insurance::where('is_active', true)->avg('coverage_percentage') ?? 0,
        ];

        return view('admin.insurances.index', compact('insurances', 'stats', 'search', 'status'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        return view('admin.insurances.form');
    }

    /**
     * Salvar nova seguradora
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'code' => ['nullable', 'string', 'max:20', 'unique:insurances,code'],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'coverage_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
        ], [
            'name.required' => 'O nome da seguradora é obrigatório.',
            'code.unique' => 'Este código já está registado.',
            'coverage_percentage.max' => 'A cobertura não pode exceder 100%.',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('insurances/logos', 'public');
        }

        $insurance = Insurance::create([
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'contact_person' => $validated['contact_person'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'coverage_percentage' => $validated['coverage_percentage'] ?? 0,
            'notes' => $validated['notes'] ?? null,
            'logo_path' => $logoPath,
            'is_active' => true,
        ]);

        Log::info('Seguradora criada', [
            'insurance_id' => $insurance->id,
            'name' => $insurance->name,
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('insurances.show', $insurance->id)
            ->with('success', "✅ Seguradora '{$insurance->name}' criada com sucesso!");
    }

    /**
     * Detalhes da seguradora
     */
    public function show($id)
    {
        $insurance = Insurance::findOrFail($id);
        
        // Pacientes vinculados a esta seguradora
        $linkedPatients = $insurance->patients()
            ->wherePivot('is_active', true)
            ->withPivot('policy_number', 'valid_from', 'valid_until', 'is_primary')
            ->limit(10)
            ->get();

        return view('admin.insurances.show', compact('insurance', 'linkedPatients'));
    }

    /**
     * Formulário de edição
     */
    public function edit($id)
    {
        $insurance = Insurance::findOrFail($id);
        return view('admin.insurances.form', compact('insurance'));
    }

    /**
     * Atualizar seguradora
     */
    public function update(Request $request, $id)
    {
        $insurance = Insurance::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'code' => ['nullable', 'string', 'max:20', 'unique:insurances,code,' . $insurance->id],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'coverage_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
        ]);

        $data = [
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'contact_person' => $validated['contact_person'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'coverage_percentage' => $validated['coverage_percentage'] ?? 0,
            'notes' => $validated['notes'] ?? null,
        ];

        if ($request->boolean('remove_logo') && $insurance->logo_path) {
            Storage::disk('public')->delete($insurance->logo_path);
            $data['logo_path'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($insurance->logo_path) {
                Storage::disk('public')->delete($insurance->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('insurances/logos', 'public');
        }

        $insurance->update($data);

        Log::info('Seguradora atualizada', [
            'insurance_id' => $insurance->id,
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('insurances.show', $insurance->id)
            ->with('success', "✅ Seguradora '{$insurance->name}' atualizada!");
    }

    /**
     * Ativar/Desativar seguradora
     */
    public function toggleStatus($id)
    {
        $insurance = Insurance::findOrFail($id);
        $insurance->update(['is_active' => !$insurance->is_active]);
        
        $status = $insurance->is_active ? 'ativada' : 'desativada';
        
        return back()->with('success', "✅ Seguradora {$status} com sucesso!");
    }

    /**
     * Excluir seguradora
     */
    public function destroy($id)
    {
        $insurance = Insurance::findOrFail($id);
        
        if ($insurance->logo_path) {
            Storage::disk('public')->delete($insurance->logo_path);
        }
        
        $insurance->delete();

        return redirect()
            ->route('insurances.index')
            ->with('success', '✅ Seguradora excluída com sucesso.');
    }
}