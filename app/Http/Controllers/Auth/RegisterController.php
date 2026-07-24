<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'phone' => ['required', 'string', 'unique:users,phone'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:4'],
            'type' => ['required', 'in:patient,staff'],
        ], [
            'phone.unique' => 'Este número de telemóvel já está registado.',
            'email.unique' => 'Este email já está registado.',
        ]);

        // Validação específica por tipo
        if ($request->type === 'patient') {
            // Paciente: PIN de 4 dígitos numéricos
            if (!preg_match('/^\d{4}$/', $request->password)) {
                $validator->errors()->add('password', 'Para pacientes, a senha deve ser um PIN de 4 dígitos numéricos (ex: 1234).');
            }
        } else {
            // Staff: Senha forte (mínimo 8 caracteres, letras e números)
            if (strlen($request->password) < 8 || !preg_match('/[A-Za-z]/', $request->password) || !preg_match('/[0-9]/', $request->password)) {
                $validator->errors()->add('password', 'Para profissionais, a senha deve ter pelo menos 8 caracteres, incluindo letras e números.');
            }
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Criar usuário
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'is_active' => true,
        ]);

        // Logar automaticamente após registo
        auth()->login($user);

        return redirect()->intended($request->type === 'patient' ? route('patient.dashboard') : route('dashboard'))
            ->with('success', 'Conta criada com sucesso! Bem-vindo(a) ao Makombe.');
    }
}