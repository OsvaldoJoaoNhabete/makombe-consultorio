<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Quote;
use App\Models\PatientActivityLog;

class PatientPortalController extends Controller
{
    public function showLogin()
    {
        return view('portal.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = trim($validated['identifier']);
        $password = $validated['password'];

        $patient = Patient::findByEmailOrPhone($identifier);

        if (!$patient) {
            throw ValidationException::withMessages([
                'identifier' => 'Credenciais não encontradas. Verifique seu email ou telefone.',
            ]);
        }

        if (!$patient->is_active) {
            throw ValidationException::withMessages([
                'identifier' => 'Sua conta está desativada. Contacte o consultório.',
            ]);
        }

        if (!Hash::check($password, $patient->password)) {
            throw ValidationException::withMessages([
                'password' => 'Senha incorreta. Tente novamente.',
            ]);
        }

        Auth::guard('patient')->login($patient, $request->boolean('remember'));

        try {
            PatientActivityLog::log(
                $patient->id,
                'login',
                "Paciente fez login no portal",
                ['ip' => $request->ip()]
            );
        } catch (\Exception $e) {
            // Ignorar erro de log
        }

        return redirect()->route('patient.dashboard');
    }

    public function dashboard()
    {
        $patient = Auth::guard('patient')->user();

        $upcomingConsultations = Consultation::where('patient_id', $patient->id)
            ->where('scheduled_at', '>=', now())
            ->whereIn('status', ['agendada', 'confirmada', 'em_andamento'])
            ->with('doctor')
            ->orderBy('scheduled_at')
            ->limit(5)
            ->get();

        $pastConsultations = Consultation::where('patient_id', $patient->id)
            ->where('status', 'concluida')
            ->with('doctor')
            ->orderByDesc('scheduled_at')
            ->limit(10)
            ->get();

        $recentQuotes = Quote::where('patient_id', $patient->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $stats = [
            'total_consultas' => Consultation::where('patient_id', $patient->id)->count(),
            'consultas_agendadas' => Consultation::where('patient_id', $patient->id)
                ->whereIn('status', ['agendada', 'confirmada', 'em_andamento'])
                ->count(),
            'consultas_concluidas' => Consultation::where('patient_id', $patient->id)
                ->where('status', 'concluida')
                ->count(),
            'total_cotacoes' => Quote::where('patient_id', $patient->id)->count(),
        ];

        return view('portal.dashboard', compact(
            'patient',
            'upcomingConsultations',
            'pastConsultations',
            'recentQuotes',
            'stats'
        ));
    }

    public function logout(Request $request)
    {
        $patient = Auth::guard('patient')->user();

        try {
            PatientActivityLog::log(
                $patient->id,
                'logout',
                "Paciente fez logout do portal",
                []
            );
        } catch (\Exception $e) {
            // Ignorar erro de log
        }

        Auth::guard('patient')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('patient.login');
    }
}