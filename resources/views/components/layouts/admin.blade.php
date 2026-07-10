<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} • {{ $title ?? 'Admin' }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Lato', sans-serif; }
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 260px; background: white; border-right: 1px solid #e5e7eb; display: flex; flex-direction: column; z-index: 50; }
        .nav-item { display: flex; align-items: center; padding: 0.75rem 1.5rem; color: #4b5563; text-decoration: none; transition: all 0.2s; font-size: 0.95rem; font-weight: 500; border-left: 4px solid transparent; }
        .nav-item:hover { background-color: #f9fafb; color: #2563eb; }
        .nav-item.active { background-color: #eff6ff; color: #2563eb; border-left-color: #2563eb; }
        .main-content { margin-left: 260px; min-height: 100vh; background-color: #f9fafb; }
        @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .sidebar.open { transform: translateX(0); } .main-content { margin-left: 0; } }
    </style>
    @livewireStyles
</head>
<body class="bg-gray-50">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <div class="p-6 border-b border-gray-200 flex items-center gap-3">
            <div class="h-10 w-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg flex items-center justify-center">
                <i class="fas fa-heartbeat text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-gray-800">Makombe</h1>
                <p class="text-xs text-gray-500 italic">Painel Admin</p>
            </div>
        </div>

        @auth
        <nav class="flex-1 overflow-y-auto py-4 space-y-1">
            <div class="px-4 mb-2 text-xs font-semibold text-gray-400 uppercase">Principal</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt w-6"></i> Dashboard
            </a>

            <a href="{{ route('admin.content.index') }}" class="nav-item {{ request()->routeIs('admin.content.*') ? 'active' : '' }}">
                <i class="fas fa-globe w-6"></i> Gestão de Conteúdo
            </a>

            <a href="{{ route('patients.index') }}" class="nav-item {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                <i class="fas fa-users w-6"></i> Pacientes
            </a>
            <a href="{{ route('consultations.index') }}" class="nav-item {{ request()->routeIs('consultations.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check w-6"></i> Consultas
            </a>
            <a href="{{ route('quotes.index') }}" class="nav-item {{ request()->routeIs('quotes.*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar w-6"></i> Cotações
            </a>

            @php $userRoles = auth()->user()->roles->pluck('name')->toArray(); @endphp
            @if(in_array('Medico', $userRoles) || in_array('Administrador', $userRoles))
                <a href="{{ route('doctor.index') }}" class="nav-item {{ request()->routeIs('doctor.*') ? 'active' : '' }}">
                    <i class="fas fa-user-md w-6"></i> Meu Atendimento
                </a>
            @endif

            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase">Gestão</div>
            <a href="{{ route('financeiro.index') }}" class="nav-item {{ request()->routeIs('financeiro.*') ? 'active' : '' }}">
                <i class="fas fa-coins w-6"></i> Financeiro
            </a>
            <a href="{{ route('notifications.index') }}" class="nav-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="fas fa-bell w-6"></i> Notificações
            </a>
            <a href="{{ route('activities.index') }}" class="nav-item {{ request()->routeIs('activities.*') ? 'active' : '' }}">
                <i class="fas fa-history w-6"></i> Atividades
            </a>

            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase">Administração</div>
            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="fas fa-user-shield w-6"></i> Utilizadores
            </a>
            <a href="{{ route('insurances.index') }}" class="nav-item {{ request()->routeIs('insurances.*') ? 'active' : '' }}">
                <i class="fas fa-shield-alt w-6"></i> Seguradoras
            </a>
            <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="fas fa-chart-pie w-6"></i> Relatórios
            </a>
        </nav>
        @endauth

        <div class="p-4 border-t border-gray-200">
            @auth
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->roles->first()?->name ?? 'Utilizador' }}</p>
                    </div>
                    <form method="POST" action="{{ route('staff.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="Sair">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </aside>

    <div class="main-content">
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <button class="md:hidden text-gray-600" onclick="toggleSidebar()">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h2 class="text-xl font-bold text-gray-800">{{ $title ?? 'Dashboard' }}</h2>
            @auth
                <form method="POST" action="{{ route('staff.logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition text-sm">
                        <i class="fas fa-sign-out-alt mr-1"></i> Sair
                    </button>
                </form>
            @endauth
        </header>

        <main class="p-6 md:p-8">
            @auth
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 flex items-center gap-3">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 flex items-center gap-3">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif
                {{ $slot }}
            @else
                <div class="flex items-center justify-center min-h-[60vh]">
                    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full text-center">
                        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-lock text-3xl text-red-600"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Acesso Restrito</h2>
                        <p class="text-gray-600 mb-6">Faça login para aceder ao sistema.</p>
                        <a href="{{ route('login') }}" class="block w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition">
                            <i class="fas fa-sign-in-alt mr-2"></i> Fazer Login
                        </a>
                    </div>
                </div>
            @endauth
        </main>

        <footer class="bg-white border-t border-gray-200 py-4 px-8 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Makombe Consultório Médico
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