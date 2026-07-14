@php
    $patient = auth()->guard('patient')->user();
    $currentRoute = request()->route()->getName() ?? '';
@endphp

<aside class="sidebar" id="sidebar">
    <!-- Logo Area -->
    <div class="p-6 border-b border-white/10 flex flex-col items-center text-center">
        <div class="w-20 h-20 bg-white rounded-2xl shadow-xl flex items-center justify-center mb-3 overflow-hidden">
            @if(file_exists(public_path('images/logo-mcm.png')))
                <img src="{{ asset('images/logo-mcm.png') }}" alt="Makombe Logo" class="w-full h-full object-contain p-2">
            @else
                <i class="fas fa-heartbeat text-4xl text-violet-700"></i>
            @endif
        </div>
        <h1 class="text-xl font-black text-white tracking-wide">MAKOMBE</h1>
        <p class="text-xs text-violet-200 italic mt-1">"Aqui você tem saúde"</p>
        <span class="mt-2 px-3 py-1 bg-white/10 rounded-full text-[10px] font-bold text-violet-200 uppercase tracking-wider">Portal do Paciente</span>
    </div>

    <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
        <a href="{{ route('patient.dashboard') }}" class="nav-item {{ $currentRoute === 'patient.dashboard' ? 'active' : '' }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="{{ route('patient.schedule') }}" class="nav-item {{ $currentRoute === 'patient.schedule' ? 'active' : '' }}">
            <i class="fas fa-calendar-plus"></i> Agendar Consulta
        </a>
        <a href="{{ route('patient.consultations') }}" class="nav-item {{ str_starts_with($currentRoute, 'patient.consultations') ? 'active' : '' }}">
            <i class="fas fa-calendar-check"></i> Minhas Consultas
        </a>
        <a href="{{ route('patient.quotes') }}" class="nav-item {{ str_starts_with($currentRoute, 'patient.quotes') ? 'active' : '' }}">
            <i class="fas fa-file-invoice-dollar"></i> Cotações
        </a>
        <a href="{{ route('patient.payments') }}" class="nav-item {{ str_starts_with($currentRoute, 'patient.payments') ? 'active' : '' }}">
            <i class="fas fa-credit-card"></i> Pagamentos
        </a>
        <a href="{{ route('patient.insurances') }}" class="nav-item {{ str_starts_with($currentRoute, 'patient.insurances') ? 'active' : '' }}">
            <i class="fas fa-shield-alt"></i> Seguradoras
        </a>
        <a href="{{ route('patient.profile') }}" class="nav-item {{ str_starts_with($currentRoute, 'patient.profile') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i> Meu Perfil
        </a>
    </nav>

    <!-- User Profile Area -->
    <div class="p-4 border-t border-white/10 bg-black/10">
        @if($patient)
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 rounded-full bg-gradient-to-br from-violet-400 to-fuchsia-500 flex items-center justify-center text-white font-bold shadow-lg border-2 border-white/20">
                    {{ strtoupper(substr($patient->full_name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white truncate">{{ $patient->full_name }}</p>
                    <p class="text-xs text-violet-300 truncate">{{ $patient->nid }}</p>
                </div>
                <form method="POST" action="{{ route('patient.logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-violet-300 hover:text-white hover:bg-white/10 p-2 rounded-lg transition" title="Sair">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        @endif
    </div>
</aside>