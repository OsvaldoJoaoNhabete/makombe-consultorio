<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status', 'all');

        $query = Specialty::query();

        // Filtro de pesquisa
        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        // Filtro de status
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $specialties = $query->withCount('users')->latest()->paginate(15);

        // Estatísticas
        $stats = [
            'total' => Specialty::count(),
            'ativas' => Specialty::where('is_active', true)->count(),
            'inativas' => Specialty::where('is_active', false)->count(),
            'com_medicos' => Specialty::has('users')->count(),
        ];

        return view('admin.specialties.index', compact('specialties', 'stats', 'search', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.specialties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'min:3', 'unique:specialties,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'O nome da especialidade é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'name.max' => 'O nome não pode exceder 100 caracteres.',
            'name.unique' => 'Já existe uma especialidade com este nome.',
            'description.max' => 'A descrição não pode exceder 500 caracteres.',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Specialty::create($validated);

        return redirect()->route('admin.specialties.index')
            ->with('success', 'Especialidade "' . $validated['name'] . '" criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $specialty = Specialty::withCount('users')->findOrFail($id);
        $medicos = $specialty->users()->with('roles')->get();
        
        return view('admin.specialties.show', compact('specialty', 'medicos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $specialty = Specialty::findOrFail($id);
        return view('admin.specialties.edit', compact('specialty'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $specialty = Specialty::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'min:3', 'unique:specialties,name,' . $specialty->id],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'O nome da especialidade é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'name.unique' => 'Já existe uma especialidade com este nome.',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $specialty->update($validated);

        return redirect()->route('admin.specialties.index')
            ->with('success', 'Especialidade "' . $validated['name'] . '" atualizada com sucesso!');
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(string $id)
    {
        $specialty = Specialty::findOrFail($id);
        $specialty->is_active = !$specialty->is_active;
        $specialty->save();

        $message = $specialty->is_active 
            ? 'Especialidade ativada com sucesso.' 
            : 'Especialidade desativada com sucesso.';

        return redirect()->route('admin.specialties.index')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $specialty = Specialty::findOrFail($id);
        
        // Verificar se há médicos associados
        if ($specialty->users()->count() > 0) {
            return redirect()->route('admin.specialties.index')
                ->with('error', 'Não é possível eliminar esta especialidade porque existem médicos associados a ela.');
        }

        $specialty->delete();

        return redirect()->route('admin.specialties.index')
            ->with('success', 'Especialidade eliminada com sucesso.');
    }
}