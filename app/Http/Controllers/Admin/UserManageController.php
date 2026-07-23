<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::all();
        $search = $request->input('search', '');
        $role = $request->input('role', 'all');
        $status = $request->input('status', 'all');

        $query = User::query()->with(['roles', 'specialty']);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role !== 'all') {
            $query->role($role);
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $users = $query->latest()->paginate(15);

        $stats = [
            'total'           => User::count(),
            'ativos'          => User::where('is_active', true)->count(),
            'inativos'        => User::where('is_active', false)->count(),
            'medicos'         => User::role('Medico')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats', 'roles', 'search', 'role', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $specialties = Specialty::all();
        return view('admin.users.create', compact('roles', 'specialties'));
    }

       public function store(Request $request)
    {
        $availableRoles = \Spatie\Permission\Models\Role::pluck('name')->toArray();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
            'role' => ['required', 'string', 'in:' . implode(',', $availableRoles)],
            'specialty_id' => ['nullable', 'exists:specialties,id'],
            'is_active' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'availabilities' => ['nullable', 'array'],
            'availabilities.*.day_of_week' => ['required_with:availabilities', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'availabilities.*.start_time' => ['required_with:availabilities', 'date_format:H:i'],
            'availabilities.*.end_time' => ['required_with:availabilities', 'date_format:H:i', 'after:availabilities.*.start_time'],
        ]);

        // Gestão do upload da foto
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . str_replace(' ', '_', $validated['name']) . '.' . $file->getClientOriginalExtension();
            $photoPath = $file->storeAs('users/photos', $fileName, 'public');
        }

        // Criar o utilizador
        $user = \App\Models\User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ?? null,
            'password' => bcrypt($validated['password']),
            'specialty_id' => $validated['specialty_id'] ?? null,
            'is_active' => $request->has('is_active'),
            'photo' => $photoPath,
            'must_change_password' => true,
        ]);

        // Atribuir a Role
        $user->assignRole($validated['role']);

        // Guardar disponibilidades
        if (isset($validated['availabilities']) && is_array($validated['availabilities'])) {
            foreach ($validated['availabilities'] as $availability) {
                if (!empty($availability['day_of_week']) && !empty($availability['start_time']) && !empty($availability['end_time'])) {
                    \App\Models\ProfessionalAvailability::create([
                        'user_id' => $user->id,
                        'day_of_week' => $availability['day_of_week'],
                        'start_time' => $availability['start_time'],
                        'end_time' => $availability['end_time'],
                        'is_active' => true,
                    ]);
                }
            }
        }

        return redirect()->route('users.index')
            ->with('success', 'Utilizador "' . $user->name . '" criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['roles', 'specialty'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with(['roles', 'specialty'])->findOrFail($id);
        $roles = Role::all();
        $specialties = Specialty::all();
        
        return view('admin.users.edit', compact('user', 'roles', 'specialties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $availableRoles = Role::pluck('name')->toArray();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8', 'max:255', 'confirmed'],
            'role' => ['required', 'string', 'in:' . implode(',', $availableRoles)],
            'specialty_id' => ['nullable', 'exists:specialties,id'],
            'is_active' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ], [
            'name.required' => 'O nome completo é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.unique' => 'Este e-mail já está em uso.',
            'phone.max' => 'O telefone não pode exceder 20 caracteres.',
            'password.min' => 'A palavra-passe deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'As palavras-passe não coincidem.',
            'role.required' => 'Deve selecionar uma função.',
        ]);

        // Gestão da foto
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $file = $request->file('photo');
            $fileName = time() . '_' . str_replace(' ', '_', $validated['name']) . '.' . $file->getClientOriginalExtension();
            $validated['photo'] = $file->storeAs('users/photos', $fileName, 'public');
        } else {
            unset($validated['photo']);
        }

        // Gestão da password
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
            $validated['must_change_password'] = false; // Resetar flag após mudança
        } else {
            unset($validated['password']);
            unset($validated['must_change_password']);
        }

        $user->update($validated);
        $user->syncRoles($validated['role']);

        return redirect()->route('users.index')->with('success', 'Utilizador "' . $user->name . '" atualizado com sucesso!');
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(string $id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $message = $user->is_active ? 'Utilizador ativado com sucesso.' : 'Utilizador desativado com sucesso.';
        return redirect()->route('users.index')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }
        
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilizador eliminado com sucesso.');
    }
}