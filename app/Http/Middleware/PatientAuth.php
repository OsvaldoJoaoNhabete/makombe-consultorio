<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('patient')->check()) {
            return redirect()->route('patient.login');
        }

        $patient = Auth::guard('patient')->user();

        if (!$patient->is_active) {
            Auth::guard('patient')->logout();
            return redirect()->route('patient.login')
                ->withErrors(['identifier' => 'Sua conta está desativada. Contacte o consultório.']);
        }

        return $next($request);
    }
}