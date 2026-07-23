<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Makombe Consultório Médico') - Painel Administrativo</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .sidebar-gradient {
            background: linear-gradient(180deg, #6d28d9 0%, #5b21b6 100%);
        }
        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .nav-item.active {
            background: rgba(255, 255, 255, 0.15);
            border-left: 4px solid #a78bfa;
        }
    </style>
</head>
<body class="bg-gray-50">

    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR -->
        <aside class="w-64 sidebar-gradient text-white flex flex-col shadow-2xl">
            <!-- Logo -->
            <div class="p-6 text-center border-b border-purple-400/30">
                <div class="w-16 h-16 bg-white rounded-xl mx-auto mb-3 flex items-center justify-center shadow-lg">
                    <span class="text-3xl font-bold text-purple-700">M</span>
                </div>
                <h1 class="text-2xl font-bold mb-1">MAKOMBE</h1>
                <p class="text-xs text-purple-200 italic">"Aqui você tem saúde"</p>
                <div class="mt-2 inline-block bg-purple-800/50 px-3 py-1 rounded-full text-xs">
                    PAINEL ADMINISTRATIVO
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4">
                <!-- PRINCIPAL -->
                <div class="px-4 mb-2">
                    <p class="text-xs text-purple-300 uppercase tracking-wider mb-3 font-semibold">Principal</p>
                    
                    <a href="{{ route('dashboard') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large w-5"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('patients.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                        <i class="fas fa-users w-5"></i>
                        <span>Pacientes</span>
                    </a>

                    <a href="{{ route('consultations.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('consultations.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check w-5"></i>
                        <span>Consultas</span>
                    </a>

                    <a href="{{ route('quotes.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('quotes.*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar w-5"></i>
                        <span>Cotações</span>
                    </a>

                    <a href="#" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200">
                        <i class="fas fa-user-md w-5"></i>
                        <span>Meu Atendimento</span>
                    </a>
                </div>

                <!-- GESTÃO -->
                <div class="px-4 mb-2">
                    <p class="text-xs text-purple-300 uppercase tracking-wider mb-3 font-semibold">Gestão</p>
                    
                    <a href="{{ route('financeiro.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.*') ? 'active' : '' }}">
                        <i class="fas fa-coins w-5"></i>
                        <span>Financeiro</span>
                    </a>

                    <a href="{{ route('reports.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span>Relatórios</span>
                    </a>

                    <a href="{{ route('activities.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('activities.*') ? 'active' : '' }}">
                        <i class="fas fa-history w-5"></i>
                        <span>Atividades</span>
                    </a>
                </div>

                <!-- ADMINISTRAÇÃO -->
                <div class="px-4 mb-2">
                    <p class="text-xs text-purple-300 uppercase tracking-wider mb-3 font-semibold">Administração</p>
                    
                    @role('Administrador|Gerente')
                    <a href="{{ route('users.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield w-5"></i>
                        <span>Utilizadores</span>
                    </a>

                    <a href="{{ route('admin.specialties.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.specialties.*') ? 'active' : '' }}">
                        <i class="fas fa-stethoscope w-5"></i>
                        <span>Especialidades</span>
                    </a>

                    <a href="{{ route('insurances.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('insurances.*') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt w-5"></i>
                        <span>Seguradoras</span>
                    </a>
                    @endrole

                    <a href="{{ route('admin.content.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.content.*') ? 'active' : '' }}">
                        <i class="fas fa-cog w-5"></i>
                        <span>Gestão de Conteúdo</span>
                    </a>
                </div>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-purple-400/30">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-purple-400 flex items-center justify-center font-bold text-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-purple-300 truncate">{{ Auth::user()->roles->first()->name ?? 'Utilizador' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- TOP HEADER -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Left Side -->
                    <div class="flex items-center gap-4">
                        <button class="md:hidden text-gray-600 hover:text-purple-600">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center gap-4">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->roles->first()->name ?? 'Utilizador' }}</p>
                        </div>
                        
                        <div class="h-10 w-10 rounded-full bg-purple-100 border-2 border-purple-300 flex items-center justify-center text-purple-700 font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                                <i class="fas fa-sign-out-alt"></i>
                                <span class="hidden sm:inline">Sair</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                @yield('content')
            </main>

            <!-- FOOTER -->
            <footer class="bg-white border-t border-gray-200 px-6 py-3">
                <p class="text-center text-sm text-gray-600">
                    © {{ date('Y') }} <span class="text-purple-600 font-semibold">Makombe Consultório Médico</span>. Todos os direitos reservados.
                </p>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        document.querySelector('button.md\\:hidden')?.addEventListener('click', function() {
            document.querySelector('aside').classList.toggle('-translate-x-full');
        });
    </script>

</body>
</html>