<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Insurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InsuranceManageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status', 'all');

        $query = Insurance::query();

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $insurances = $query->withCount('patients')->latest()->paginate(15);

        $stats = [
            'total' => Insurance::count(),
            'ativas' => Insurance::where('is_active', true)->count(),
            'inativas' => Insurance::where('is_active', false)->count(),
            'com_pacientes' => Insurance::has('patients')->count(),
        ];

        return view('admin.insurances.index', compact('insurances', 'stats', 'search', 'status'));
    }

    public function create()
    {
        return view('admin.insurances.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', 'min:3', 'unique:insurances,name'],
            'code' => ['nullable', 'string', 'max:50', 'unique:insurances,code'],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'coverage_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
            'logo_path' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required' => 'O nome da seguradora é obrigatório.',
            'name.unique' => 'Já existe uma seguradora com este nome.',
            'code.unique' => 'Já existe uma seguradora com este código.',
            'coverage_percentage.max' => 'A percentagem de cobertura não pode exceder 100%.',
            'logo_path.image' => 'O ficheiro deve ser uma imagem.',
            'logo_path.max' => 'O logótipo não pode exceder 2MB.',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo_path')) {
            $file = $request->file('logo_path');
            $fileName = time() . '_' . str_replace(' ', '_', $validated['name']) . '.' . $file->getClientOriginalExtension();
            $validated['logo_path'] = $file->storeAs('insurances/logos', $fileName, 'public');
        }

        Insurance::create($validated);

        return redirect()->route('insurances.index')
            ->with('success', 'Seguradora "' . $validated['name'] . '" criada com sucesso!');
    }

    public function show(string $id)
    {
        $insurance = Insurance::withCount('patients')->findOrFail($id);
        return view('admin.insurances.show', compact('insurance'));
    }

    public function edit(string $id)
    {
        $insurance = Insurance::findOrFail($id);
        return view('admin.insurances.edit', compact('insurance'));
    }

    public function update(Request $request, string $id)
    {
        $insurance = Insurance::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', 'min:3', 'unique:insurances,name,' . $insurance->id],
            'code' => ['nullable', 'string', 'max:50', 'unique:insurances,code,' . $insurance->id],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'coverage_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
            'logo_path' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo_path')) {
            if ($insurance->logo_path) {
                Storage::disk('public')->delete($insurance->logo_path);
            }
            $file = $request->file('logo_path');
            $fileName = time() . '_' . str_replace(' ', '_', $validated['name']) . '.' . $file->getClientOriginalExtension();
            $validated['logo_path'] = $file->storeAs('insurances/logos', $fileName, 'public');
        } else {
            unset($validated['logo_path']);
        }

        $insurance->update($validated);

        return redirect()->route('insurances.index')
            ->with('success', 'Seguradora "' . $validated['name'] . '" atualizada com sucesso!');
    }

    public function toggleStatus(string $id)
    {
        $insurance = Insurance::findOrFail($id);
        $insurance->is_active = !$insurance->is_active;
        $insurance->save();

        $message = $insurance->is_active ? 'Seguradora ativada com sucesso.' : 'Seguradora desativada com sucesso.';
        return redirect()->route('insurances.index')->with('success', $message);
    }

    public function destroy(string $id)
    {
        $insurance = Insurance::findOrFail($id);
        
        if ($insurance->patients()->count() > 0) {
            return redirect()->route('insurances.index')
                ->with('error', 'Não é possível eliminar esta seguradora porque existem pacientes associados a ela.');
        }

        if ($insurance->logo_path) {
            Storage::disk('public')->delete($insurance->logo_path);
        }

        $insurance->delete();

        return redirect()->route('insurances.index')
            ->with('success', 'Seguradora eliminada com sucesso.');
    }
}