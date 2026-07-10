<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PasswordRecoveryController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Mostrar formulário de recuperação
     */
    public function showRecoveryForm()
    {
        return view('portal.password-recovery');
    }

    /**
     * Processar pedido de recuperação
     */
    public function recover(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'size:9'],
            'bi_number' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
        ], [
            'phone.required' => 'O número de telefone é obrigatório.',
            'phone.size' => 'O telefone deve ter 9 dígitos.',
            'bi_number.required' => 'O número do BI é obrigatório.',
            'birth_date.required' => 'A data de nascimento é obrigatória.',
        ]);

        // Limpar dados
        $phoneClean = preg_replace('/[^0-9]/', '', $validated['phone']);
        $biClean = strtoupper(trim($validated['bi_number']));

        // Buscar paciente com os 3 dados
        $patient = Patient::where('phone', $phoneClean)
            ->where('bi_number', $biClean)
            ->where('birth_date', $validated['birth_date'])
            ->where('is_active', true)
            ->first();

        if (!$patient) {
            return back()
                ->withErrors(['phone' => 'Os dados informados não correspondem a nenhum paciente ativo. Verifique e tente novamente.'])
                ->withInput();
        }

        // Gerar PIN de 6 dígitos
        $pin = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Salvar PIN temporário (expira em 24h)
        $patient->update([
            'password_reset_token' => Hash::make($pin),
            'password_reset_expires' => now()->addHours(24),
        ]);

        Log::info('PIN de recuperação gerado', [
            'patient_id' => $patient->id,
            'phone' => $patient->phone,
        ]);

        // Tentar enviar por WhatsApp
        $whatsappSent = false;
        try {
            $message = "🔐 *MAKOMBE - RECUPERAÇÃO DE SENHA*\n\n";
            $message .= "Olá {$patient->full_name},\n\n";
            $message .= "Seu PIN temporário de acesso é:\n";
            $message .= "*{$pin}*\n\n";
            $message .= "⚠️ *IMPORTANTE:*\n";
            $message .= "• Este PIN expira em 24 horas\n";
            $message .= "• Pode ser usado apenas 1 vez\n";
            $message .= "• Após o login, crie uma nova senha\n\n";
            $message .= "Se não solicitou este PIN, ignore esta mensagem.\n\n";
            $message .= "_Makombe Consultório Médico_\n";
            $message .= "📞 +258 84 123 4567";

            $whatsappSent = $this->whatsappService->sendCustomMessage($patient->phone, $message);
        } catch (\Exception $e) {
            Log::warning('Erro ao enviar WhatsApp de recuperação: ' . $e->getMessage());
        }

        return redirect()
            ->route('patient.password.reset', ['token' => $pin])
            ->with('pin', $pin)
            ->with('whatsapp_sent', $whatsappSent)
            ->with('patient_name', $patient->full_name)
            ->with('success', 'PIN de recuperação gerado!');
    }

    /**
     * Mostrar formulário de reset de senha
     */
    public function showResetForm(Request $request)
    {
        $pin = $request->query('token') ?? session('pin');
        
        if (!$pin) {
            return redirect()->route('patient.password.recovery');
        }

        return view('portal.password-reset', compact('pin'));
    }

    /**
     * Processar reset de senha
     */
    public function reset(Request $request)
    {
        $validated = $request->validate([
            'pin' => ['required', 'string', 'size:6'],
            'phone' => ['required', 'string', 'size:9'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'pin.required' => 'O PIN é obrigatório.',
            'pin.size' => 'O PIN deve ter 6 dígitos.',
            'phone.required' => 'O telefone é obrigatório.',
            'new_password.required' => 'A nova senha é obrigatória.',
            'new_password.min' => 'A senha deve ter no mínimo 6 caracteres.',
            'new_password.confirmed' => 'As senhas não coincidem.',
        ]);

        $phoneClean = preg_replace('/[^0-9]/', '', $validated['phone']);

        // Buscar paciente
        $patient = Patient::where('phone', $phoneClean)
            ->where('is_active', true)
            ->first();

        if (!$patient) {
            return back()->withErrors(['phone' => 'Paciente não encontrado.']);
        }

        // Verificar PIN
        if (!$patient->password_reset_token || !$patient->password_reset_expires) {
            return back()->withErrors(['pin' => 'Nenhum PIN de recuperação encontrado. Solicite um novo.']);
        }

        if (now()->greaterThan($patient->password_reset_expires)) {
            return back()->withErrors(['pin' => 'PIN expirado. Solicite um novo.']);
        }

        if (!Hash::check($validated['pin'], $patient->password_reset_token)) {
            return back()->withErrors(['pin' => 'PIN incorreto. Verifique e tente novamente.']);
        }

        // Atualizar senha, limpar token e marcar primeiro acesso como feito
        $patient->update([
            'password' => Hash::make($validated['new_password']),
            'password_reset_token' => null,
            'password_reset_expires' => null,
            'first_login_at' => now(), // Marcar que já fez primeiro acesso
        ]);

        Log::info('Senha recuperada com sucesso', [
            'patient_id' => $patient->id,
        ]);

        return redirect()
            ->route('patient.login')
            ->with('success', '✅ Senha alterada com sucesso! Faça login com sua nova senha.');
    }
}