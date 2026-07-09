<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserManageController extends Controller
{
    /**
     * Lista de utilizadores
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role', 'all');
        $status = $request->input('status', 'all');

        $query = User::with('roles');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        if ($role !== 'all') {
            $query->whereHas('roles', function($q) use ($role) {
                $q->where('name', $role);
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $users = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $stats = [
            'total' => User::count(),
            'ativos' => User::where('is_active', true)->count(),
            'inativos' => User::where('is_active', false)->count(),
            'medicos' => User::role('Medico')->count(),
        ];

        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'stats', 'roles', 'search', 'role', 'status'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.form', compact('roles'));
    }

    /**
     * Salvar novo utilizador
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'exists:roles,name'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ], [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.unique' => 'Este email já está registado.',
            'role.required' => 'Selecione uma função.',
            'password.required' => 'A senha é obrigatória.',
            'password.confirmed' => 'As senhas não coincidem.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users/photos', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
            'is_active' => true,
            'photo_path' => $photoPath,
        ]);

        $user->assignRole($validated['role']);

        Log::info('Utilizador criado', [
            'user_id' => $user->id,
            'name' => $user->name,
            'role' => $validated['role'],
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('users.show', $user->id)
            ->with('success', "✅ Utilizador criado com sucesso! Função: {$validated['role']}");
    }

    /**
     * Detalhes do utilizador
     */
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Formulário de edição
     */
    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::orderBy('name')->get();
        return view('admin.users.form', compact('user', 'roles'));
    }

    /**
     * Atualizar utilizador
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'exists:roles,name'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'phone' => $validated['phone'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        if ($request->boolean('remove_photo') && $user->photo_path) {
            Storage::disk('public')->delete($user->photo_path);
            $data['photo_path'] = null;
        }

        if ($request->hasFile('photo')) {
            if ($user->photo_path) {
                Storage::disk('public')->delete($user->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('users/photos', 'public');
        }

        $user->update($data);

        // Atualizar role
        $user->syncRoles([$validated['role']]);

        Log::info('Utilizador atualizado', [
            'user_id' => $user->id,
            'role' => $validated['role'],
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('users.show', $user->id)
            ->with('success', '✅ Utilizador atualizado com sucesso!');
    }

    /**
     * Ativar/Desativar utilizador
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', '❌ Não pode desativar a sua própria conta.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'ativado' : 'desativado';

        return back()->with('success', "✅ Utilizador {$status} com sucesso!");
    }

    /**
     * Excluir utilizador
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Proteção: não pode excluir a si mesmo
        if ($user->id === Auth::id()) {
            return back()->with('error', ' Não pode excluir a sua própria conta.');
        }

        // Proteção: não pode excluir o último administrador
        if ($user->hasRole('Administrador') && User::role('Administrador')->count() <= 1) {
            return back()->with('error', '❌ Não pode excluir o último administrador do sistema.');
        }

        if ($user->photo_path) {
            Storage::disk('public')->delete($user->photo_path);
        }

        $user->delete();

        Log::info('Utilizador excluído', [
            'user_id' => $user->id,
            'deleted_by' => Auth::id(),
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', '✅ Utilizador excluído com sucesso.');
    }
}