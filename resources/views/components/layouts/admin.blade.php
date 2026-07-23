<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Makombe') }} • {{ $title ?? 'Painel Admin' }}</title>
    
    <!-- Tailwind CSS e Chart.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Fonte Poppins (Preferência do Sistema) -->
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Lato', sans-serif; }
        
        .sidebar { 
            position: fixed; top: 0; left: 0; height: 100vh; width: 270px; 
            background: linear-gradient(180deg, #4c1d95 0%, #5b21b6 50%, #6d28d9 100%); 
            display: flex; flex-direction: column; z-index: 50; 
            transition: transform 0.3s ease;
        }
        
        .nav-item { 
            display: flex; align-items: center; padding: 0.85rem 1.5rem; 
            color: #ddd6fe; text-decoration: none; transition: all 0.2s ease; 
            font-size: 0.95rem; font-weight: 500; border-left: 4px solid transparent; 
        }
        .nav-item:hover { 
            background-color: rgba(255, 255, 255, 0.1); 
            color: #ffffff; 
            border-left-color: #a78bfa;
        }
        .nav-item.active { 
            background-color: rgba(255, 255, 255, 0.15); 
            color: #ffffff; 
            border-left-color: #ffffff;
            font-weight: 700;
        }
        .nav-item i { width: 24px; text-align: center; margin-right: 12px; font-size: 1.1rem; }
        
        .main-content { margin-left: 270px; min-height: 100vh; background-color: #f8fafc; }
        
        @media (max-width: 768px) { 
            .sidebar { transform: translateX(-100%); } 
            .sidebar.open { transform: translateX(0); } 
            .main-content { margin-left: 0; } 
        }
        
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 3px; }
    </style>
    @livewireStyles
</head>
<body class="bg-slate-50 text-slate-800">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

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
            <span class="mt-2 px-3 py-1 bg-white/10 rounded-full text-[10px] font-bold text-violet-200 uppercase tracking-wider">Painel Administrativo</span>
        </div>

        @auth
        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
            <div class="px-3 mb-2 text-[10px] font-bold text-violet-300 uppercase tracking-widest">Principal</div>
            
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="{{ route('profile.edit') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                        <span>Meu Perfil</span>
                    </a>
            <a href="{{ route('patients.index') }}" class="nav-item {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Pacientes
            </a>
            <a href="{{ route('consultations.index') }}" class="nav-item {{ request()->routeIs('consultations.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i> Consultas
            </a>
            <a href="{{ route('quotes.index') }}" class="nav-item {{ request()->routeIs('quotes.*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar"></i> Cotações
            </a>

            {{-- CORREÇÃO AQUI: Uso do operador nullsafe (?->) e fallback (?? []) --}}
            @php 
                $userRoles = auth()->user()->roles?->pluck('name')->toArray() ?? []; 
            @endphp
            
            @if(in_array('Medico', $userRoles) || in_array('Administrador', $userRoles) || in_array('Gerente', $userRoles))
                <a href="{{ route('doctor.index') }}" class="nav-item {{ request()->routeIs('doctor.*') ? 'active' : '' }}">
                    <i class="fas fa-user-md"></i> Meu Atendimento
                </a>
            @endif

            <div class="px-3 mt-6 mb-2 text-[10px] font-bold text-violet-300 uppercase tracking-widest">Gestão</div>
            
            <a href="{{ route('financeiro.index') }}" class="nav-item {{ request()->routeIs('financeiro.*') ? 'active' : '' }}">
                <i class="fas fa-coins"></i> Financeiro
            </a>
            <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> Relatórios
            </a>
            <a href="{{ route('activities.index') }}" class="nav-item {{ request()->routeIs('activities.*') ? 'active' : '' }}">
                <i class="fas fa-history"></i> Atividades
            </a>

            @if(in_array('Administrador', $userRoles) || in_array('Gerente', $userRoles))
                <div class="px-3 mt-6 mb-2 text-[10px] font-bold text-violet-300 uppercase tracking-widest">Administração</div>
                
                <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i> Utilizadores
                </a>
                <a href="{{ route('admin.specialties.index') }}" class="nav-item {{ request()->routeIs('admin.specialties.*') ? 'active' : '' }}">
                    <i class="fas fa-stethoscope"></i> Especialidades
                </a>
                <a href="{{ route('insurances.index') }}" class="nav-item {{ request()->routeIs('insurances.*') ? 'active' : '' }}">
                    <i class="fas fa-shield-alt"></i> Seguradoras
                </a>
                <a href="{{ route('admin.content.index') }}" class="nav-item {{ request()->routeIs('admin.content.*') ? 'active' : '' }}">
                    <i class="fas fa-globe"></i> Gestão de Conteúdo
                </a>
            @endif
        </nav>
        @endauth

        <!-- User Profile Area -->
        <div class="p-4 border-t border-white/10 bg-black/10">
            @auth
                <div class="flex items-center gap-3">
                    <div class="h-11 w-11 rounded-full bg-gradient-to-br from-violet-400 to-fuchsia-500 flex items-center justify-center text-white font-bold shadow-lg border-2 border-white/20 overflow-hidden">
                        {{-- Verifica se existe foto, senão mostra a inicial --}}
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Foto" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
                        {{-- CORREÇÃO AQUI: Uso do operador nullsafe (?->) --}}
                        <p class="text-xs text-violet-300 truncate">{{ auth()->user()->roles?->first()?->name ?? 'Utilizador' }}</p>
                    </div>
                        <form method="POST" action="{{ route('logout') }}">                        @csrf
                        <button type="submit" class="text-violet-300 hover:text-white hover:bg-white/10 p-2 rounded-lg transition" title="Sair">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </aside>

    <div class="main-content">
        <!-- Top Header -->
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-violet-700 text-xl" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="text-xl font-bold text-slate-800">{{ $title ?? 'Dashboard' }}</h2>
            </div>
            
            @auth
                <div class="flex items-center gap-3">
                    <span class="hidden sm:inline text-sm text-slate-500">
                        <i class="far fa-calendar-alt mr-1"></i> {{ now()->format('d/m/Y') }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition text-sm font-semibold flex items-center gap-2">
                            <i class="fas fa-sign-out-alt"></i> <span class="hidden sm:inline">Sair</span>
                        </button>
                    </form>
                </div>
            @endauth
        </header>

        <main class="p-6 md:p-8">
            @auth
                @if (session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 flex items-center gap-3 shadow-sm animate-fade-in">
                        <i class="fas fa-check-circle text-xl"></i> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 flex items-center gap-3 shadow-sm animate-fade-in">
                        <i class="fas fa-exclamation-circle text-xl"></i> {{ session('error') }}
                    </div>
                @endif
                
                {{ $slot }}
            @else
                <div class="flex items-center justify-center min-h-[60vh]">
                    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full text-center border border-slate-100">
                        <div class="w-20 h-20 bg-violet-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-lock text-3xl text-violet-600"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Acesso Restrito</h2>
                        <p class="text-gray-600 mb-6">Faça login para aceder ao sistema administrativo.</p>
                        <a href="{{ route('login') }}" class="block w-full py-3 px-4 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl transition shadow-lg">
                            <i class="fas fa-sign-in-alt mr-2"></i> Fazer Login
                        </a>
                    </div>
                </div>
            @endauth
        </main>

        <footer class="bg-white border-t border-slate-200 py-4 px-8 text-center text-sm text-slate-500">
            &copy; {{ date('Y') }} <span class="font-semibold text-violet-700">Makombe Consultório Médico</span>. Todos os direitos reservados.
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebar-overlay').classList.toggle('hidden');
        }
    </script>
    @livewireScripts
    @stack('scripts')
</body>
</html>