<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PatientRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('portal.register');
    }

    public function register(Request $request)
    {
        $phoneClean = preg_replace('/[^0-9]/', '', $request->input('phone', ''));
        $biClean = strtoupper(trim($request->input('bi_number', '')));

        $validated = $request->validate([
            'full_name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-zA-ZÀ-ÿ\s\-\']+$/'
            ],
            'bi_number' => [
                'required',
                'string',
                'regex:/^[0-9]{12}[A-Z]$/',
                'unique:patients,bi_number'
            ],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:masculino,feminino'],
            'phone' => [
                'required',
                'string',
                'size:9',
                'regex:/^[89][0-9]{8}$/',
                'unique:patients,phone'
            ],
            'email' => ['required', 'email:rfc,dns', 'max:150', 'unique:patients,email'],
            'address' => ['nullable', 'string', 'max:500'],
            'terms' => ['accepted'],
        ], [
            'full_name.required' => 'O nome completo é obrigatório.',
            'full_name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'full_name.max' => 'O nome não pode ter mais de 50 caracteres.',
            'full_name.regex' => 'O nome deve conter apenas letras, espaços e hífens. Números não são permitidos.',
            
            'bi_number.required' => 'O número do BI é obrigatório.',
            'bi_number.regex' => 'O BI deve ter 12 números seguidos de 1 letra maiúscula (ex: 123456789012A).',
            'bi_number.unique' => 'Este BI já está registado no sistema.',
            
            'birth_date.required' => 'A data de nascimento é obrigatória.',
            'birth_date.before' => 'A data de nascimento deve ser anterior a hoje.',
            
            'gender.required' => 'Selecione o género.',
            'gender.in' => 'Género inválido.',
            
            'phone.required' => 'O número de telefone é obrigatório.',
            'phone.size' => 'O telefone deve ter exatamente 9 dígitos.',
            'phone.regex' => 'Número de telefone inválido. Deve começar com 8 ou 9.',
            'phone.unique' => 'Este telefone já está registado. Se já tem conta, faça login.',
            
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'Digite um email válido.',
            'email.unique' => 'Este email já está registado.',
            
            'terms.accepted' => 'Você deve aceitar os Termos e Condições.',
        ]);

        try {
            DB::beginTransaction();

            // Gerar NID automaticamente
            $nid = Patient::generateNextNid();

            // Gerar senha temporária de 6 dígitos
            $temporaryPassword = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

            // Criar paciente
            $patient = Patient::create([
                'full_name' => ucwords(strtolower($validated['full_name'])),
                'nid' => $nid,
                'bi_number' => $biClean,
                'birth_date' => $validated['birth_date'],
                'gender' => $validated['gender'],
                'phone' => $phoneClean,
                'email' => strtolower($validated['email']),
                'address' => $validated['address'] ?? null,
                'password' => Hash::make($temporaryPassword),
                'email_verified_at' => now(),
                'is_active' => true,
                'created_by' => null,
            ]);

            DB::commit();

            Log::info('Novo paciente registado', [
                'patient_id' => $patient->id,
                'nid' => $nid,
            ]);

            // Login automático
            Auth::guard('patient')->login($patient);

            // Redirecionar para página de sucesso com credenciais
            return redirect()->route('patient.register.success', [
                'nid' => $patient->nid,
                'email' => $patient->email,
                'phone' => $patient->phone,
                'password' => $temporaryPassword,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erro ao registar paciente', ['error' => $e->getMessage()]);
            
            return back()
                ->withErrors(['full_name' => 'Ocorreu um erro. Por favor, tente novamente.'])
                ->withInput();
        }
    }

    public function registerSuccess(Request $request)
    {
        $nid = $request->query('nid');
        $email = $request->query('email');
        $phone = $request->query('phone');
        $password = $request->query('password');

        if (!$nid || !$email || !$password) {
            return redirect()->route('patient.dashboard');
        }

        return view('portal.register-success', compact('nid', 'email', 'phone', 'password'));
    }

    public function showTerms()
    {
        return view('portal.terms');
    }

    public function showPrivacy()
    {
        return view('portal.privacy');
    }
}