<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class PatientManageController extends Controller
{
    /**
     * Lista de pacientes com filtros
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', 'all');
        $gender = $request->input('gender', 'all');

        $query = Patient::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('nid', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($gender !== 'all') {
            $query->where('gender', $gender);
        }

        $patients = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $stats = [
            'total' => Patient::count(),
            'ativos' => Patient::where('is_active', true)->count(),
            'inativos' => Patient::where('is_active', false)->count(),
            'hoje' => Patient::whereDate('created_at', today())->count(),
        ];

        return view('admin.patients.index', compact('patients', 'stats', 'search', 'status', 'gender'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        return view('admin.patients.form');
    }

    /**
     * Salvar novo paciente
     */
    public function store(Request $request)
    {
        $phoneClean = preg_replace('/[^0-9]/', '', $request->input('phone', ''));

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'min:3', 'max:150'],
            'bi_number' => ['nullable', 'string', 'max:20', 'unique:patients,bi_number'],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:masculino,feminino,outro'],
            'phone' => ['required', 'string', 'size:9', 'unique:patients,phone'],
            'email' => ['required', 'email', 'max:150', 'unique:patients,email'],
            'address' => ['nullable', 'string', 'max:500'],
            'medical_history' => ['nullable', 'string', 'max:2000'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ], [
            'full_name.required' => 'O nome completo é obrigatório.',
            'phone.required' => 'O telefone é obrigatório.',
            'phone.unique' => 'Este telefone já está registado.',
            'email.required' => 'O email é obrigatório.',
            'email.unique' => 'Este email já está registado.',
            'password.required' => 'A senha é obrigatória.',
            'password.confirmed' => 'As senhas não coincidem.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('patients/photos', 'public');
        }

        $nid = Patient::generateNextNid();

        $patient = Patient::create([
            'full_name' => $validated['full_name'],
            'nid' => $nid,
            'bi_number' => $validated['bi_number'] ?? null,
            'birth_date' => $validated['birth_date'],
            'gender' => $validated['gender'],
            'phone' => $phoneClean,
            'email' => strtolower($validated['email']),
            'address' => $validated['address'] ?? null,
            'medical_history' => $validated['medical_history'] ?? null,
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
            'is_active' => true,
            'photo_path' => $photoPath,
            'created_by' => Auth::id(),
        ]);

        Log::info('Paciente criado', [
            'patient_id' => $patient->id,
            'nid' => $nid,
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('patients.show', $patient->id)
            ->with('success', "✅ Paciente criado com sucesso! NID: {$nid}");
    }

    /**
     * Detalhes do paciente
     */
    public function show($id)
    {
        $patient = Patient::with(['consultations', 'insurances'])->findOrFail($id);
        
        $recentConsultations = $patient->consultations()
            ->with('doctor')
            ->orderByDesc('scheduled_at')
            ->limit(5)
            ->get();

        return view('admin.patients.show', compact('patient', 'recentConsultations'));
    }

    /**
     * Formulário de edição
     */
    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return view('admin.patients.form', compact('patient'));
    }

    /**
     * Atualizar paciente
     */
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $phoneClean = preg_replace('/[^0-9]/', '', $request->input('phone', ''));

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'min:3', 'max:150'],
            'bi_number' => ['nullable', 'string', 'max:20', 'unique:patients,bi_number,' . $patient->id],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:masculino,feminino,outro'],
            'phone' => ['required', 'string', 'size:9', 'unique:patients,phone,' . $patient->id],
            'email' => ['required', 'email', 'max:150', 'unique:patients,email,' . $patient->id],
            'address' => ['nullable', 'string', 'max:500'],
            'medical_history' => ['nullable', 'string', 'max:2000'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);

        $data = [
            'full_name' => $validated['full_name'],
            'bi_number' => $validated['bi_number'] ?? null,
            'birth_date' => $validated['birth_date'],
            'gender' => $validated['gender'],
            'phone' => $phoneClean,
            'email' => strtolower($validated['email']),
            'address' => $validated['address'] ?? null,
            'medical_history' => $validated['medical_history'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        if ($request->boolean('remove_photo') && $patient->photo_path) {
            Storage::disk('public')->delete($patient->photo_path);
            $data['photo_path'] = null;
        }

        if ($request->hasFile('photo')) {
            if ($patient->photo_path) {
                Storage::disk('public')->delete($patient->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('patients/photos', 'public');
        }

        $patient->update($data);

        Log::info('Paciente atualizado', [
            'patient_id' => $patient->id,
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('patients.show', $patient->id)
            ->with('success', '✅ Paciente atualizado com sucesso!');
    }

    /**
     * Ativar/Desativar paciente
     */
    public function toggleStatus($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->update(['is_active' => !$patient->is_active]);
        
        $status = $patient->is_active ? 'ativado' : 'desativado';
        
        return back()->with('success', "✅ Paciente {$status} com sucesso!");
    }

    /**
     * Excluir paciente
     */
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        
        if ($patient->photo_path) {
            Storage::disk('public')->delete($patient->photo_path);
        }
        
        $patient->delete();

        return redirect()
            ->route('patients.index')
            ->with('success', '✅ Paciente excluído com sucesso.');
    }
}