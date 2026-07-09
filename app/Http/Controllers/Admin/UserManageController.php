<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManageController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->orderByDesc('created_at')->paginate(15);
        $stats = [
            'total' => User::count(),
            'ativos' => User::where('is_active', true)->count(),
            'inativos' => User::where('is_active', false)->count(),
        ];
        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.form', compact('roles'));
    }

    public function store(Request $request)
    {
        return back()->with('info', 'Funcionalidade em desenvolvimento.');
    }

    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::orderBy('name')->get();
        return view('admin.users.form', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        return back()->with('info', 'Funcionalidade em desenvolvimento.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'Status atualizado.');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Utilizador excluído.');
    }
}