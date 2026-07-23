<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FirstLoginController extends Controller
{
    /**
     * Mostra o formulário de mudança de palavra-passe.
     */
    public function show()
    {
        return view('auth.first-login');
    }

    /**
     * Processa a atualização da palavra-passe.
     */
    public function update(Request $request)
    {
        // Validação rigorosa
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ], [
            'password.required' => 'A nova palavra-passe é obrigatória.',
            'password.min' => 'A palavra-passe deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'As palavras-passe não coincidem.',
            'password.regex' => 'A palavra-passe deve conter pelo menos uma letra maiúscula, uma minúscula e um número.',
        ]);

        // Atualizar o utilizador autenticado
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->must_change_password = false; // Remove a flag
        $user->save();

        return redirect()->route('dashboard')
            ->with('success', 'Palavra-passe alterada com sucesso! Bem-vindo ao sistema Makombe.');
    }
}