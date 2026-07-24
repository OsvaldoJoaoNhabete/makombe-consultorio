<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Mostrar formulário de login unificado
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Processar login unificado (Email ou Telefone)
     * Sugestão 2: Usar número de celular para fazer login também
     */
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'identifier.required' => 'Insira seu email ou número de telefone.',
            'password.required' => 'Insira sua senha ou PIN.',
        ]);

        $identifier = $request->input('identifier');
        $password = $request->input('password');

        // Determinar se o identificador é um email ou um telefone
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // Buscar usuário na base de dados
        $user = User::where($field, $identifier)->first();

        // Verificar se o usuário existe, está ativo e a senha/PIN está correta
        if (!$user || !$user->is_active || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'identifier' => 'Credenciais incorretas ou conta inativa.',
            ]);
        }

        // Login bem-sucedido
        Auth::login($user, $request->boolean('remember'));

        // Redirecionamento inteligente baseado no tipo de usuário
        if ($user->type === 'patient') {
            return redirect()->intended(route('patient.dashboard'));
        }

        // Staff: redirecionar para o dashboard administrativo
        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('welcome')->with('success', 'Sessão terminada com sucesso.');
    }
}