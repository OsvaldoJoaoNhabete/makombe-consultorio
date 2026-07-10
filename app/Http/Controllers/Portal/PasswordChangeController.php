<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    /**
     * Mostrar formulário de alteração de senha (primeiro acesso)
     */
    public function showChangeForm()
    {
        $patient = Auth::guard('patient')->user();

        if (!$patient->needsPasswordChange()) {
            return redirect()->route('patient.dashboard')
                ->with('info', 'Você já alterou sua senha anteriormente.');
        }

        return view('portal.change-password', compact('patient'));
    }

    /**
     * Processar alteração de senha
     */
    public function change(Request $request)
    {
        $patient = Auth::guard('patient')->user();

        $validated = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'A senha atual é obrigatória.',
            'new_password.required' => 'A nova senha é obrigatória.',
            'new_password.min' => 'A nova senha deve ter no mínimo 6 caracteres.',
            'new_password.confirmed' => 'As senhas não coincidem.',
        ]);

        // Verificar senha atual
        if (!Hash::check($validated['current_password'], $patient->password)) {
            return back()->withErrors(['current_password' => 'A senha atual está incorreta.']);
        }

        // Verificar se a nova senha é diferente da atual
        if (Hash::check($validated['new_password'], $patient->password)) {
            return back()->withErrors(['new_password' => 'A nova senha deve ser diferente da atual.']);
        }

        // Atualizar senha e marcar primeiro acesso
        $patient->update([
            'password' => Hash::make($validated['new_password']),
            'first_login_at' => now(),
        ]);

        return redirect()
            ->route('patient.dashboard')
            ->with('success', '✅ Senha alterada com sucesso! A partir de agora, use esta nova senha para fazer login.');
    }

    /**
     * Adiar alteração de senha (volta no próximo login)
     */
    public function postpone()
    {
        return redirect()->route('patient.dashboard')
            ->with('info', 'Lembre-se de alterar sua senha em "Meu Perfil" quando puder.');
    }
}