<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class PatientRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('portal.register');
    }

    public function register(Request $request)
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
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'terms' => ['accepted'],
        ], [
            'full_name.required' => 'O nome completo é obrigatório.',
            'phone.required' => 'O número de telefone é obrigatório.',
            'phone.unique' => 'Este número de telefone já está registado.',
            'email.required' => 'O email é obrigatório.',
            'email.unique' => 'Este email já está registado.',
            'password.required' => 'A senha é obrigatória.',
            'password.confirmed' => 'As senhas não coincidem.',
            'terms.accepted' => 'Você deve aceitar os Termos e Condições.',
        ]);

        try {
            DB::beginTransaction();

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
                'password' => Hash::make($validated['password']),
                'email_verified_at' => now(),
                'is_active' => true,
                'created_by' => null,
            ]);

            DB::commit();

            Log::info('Novo paciente registado', [
                'patient_id' => $patient->id,
                'nid' => $nid,
            ]);

            Auth::guard('patient')->login($patient);

            return redirect()->route('patient.register.success', [
                'nid' => $patient->nid,
                'email' => $patient->email,
                'phone' => $patient->phone,
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

        if (!$nid || !$email) {
            return redirect()->route('patient.dashboard');
        }

        return view('portal.register-success', compact('nid', 'email', 'phone'));
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