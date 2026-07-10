<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Consultas • Portal do Paciente - Makombe</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Lato', sans-serif; }
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 260px; background: white; border-right: 1px solid #e5e7eb; display: flex; flex-direction: column; z-index: 50; }
        .nav-item { display: flex; align-items: center; padding: 0.75rem 1.5rem; color: #4b5563; text-decoration: none; transition: all 0.2s; font-size: 0.95rem; font-weight: 500; border-left: 4px solid transparent; }
        .nav-item:hover { background-color: #f9fafb; color: #10b981; }
        .nav-item.active { background-color: #ecfdf5; color: #10b981; border-left-color: #10b981; }
        .main-content { margin-left: 260px; min-height: 100vh; background-color: #f9fafb; }
        @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .sidebar.open { transform: translateX(0); } .main-content { margin-left: 0; } }
    </style>
</head>
<body class="bg-gray-50">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-heartbeat text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-800">MAKOMBE</h1>
                    <p class="text-xs text-gray-500 italic">Portal do Paciente</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 space-y-1">
            <a href="{{ route('patient.dashboard') }}" class="nav-item"><i class="fas fa-home w-6"></i> Dashboard</a>
            <a href="{{ route('patient.schedule') }}" class="nav-item"><i class="fas fa-calendar-plus w-6"></i> Agendar Consulta</a>
            <a href="{{ route('patient.consultations') }}" class="nav-item active"><i class="fas fa-calendar-check w-6"></i> Minhas Consultas</a>
            <a href="{{ route('patient.quotes') }}" class="nav-item"><i class="fas fa-file-invoice-dollar w-6"></i> Cotações</a>
            <a href="{{ route('patient.payments') }}" class="nav-item"><i class="fas fa-credit-card w-6"></i> Pagamentos</a>
            <a href="{{ route('patient.insurances') }}" class="nav-item"><i class="fas fa-shield-alt w-6"></i> Seguradoras</a>
            <a href="{{ route('patient.profile') }}" class="nav-item"><i class="fas fa-user w-6"></i> Meu Perfil</a>
        </nav>

        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr($patient->full_name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $patient->full_name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $patient->email }}</p>
                </div>
                <form method="POST" action="{{ route('patient.logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-600 transition"><i class="fas fa-sign-out-alt"></i></button>
                </form>
            </div>
        </div>
    </aside>

    <div class="main-content">
        <header class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <button class="md:hidden text-gray-600" onclick="toggleSidebar()"><i class="fas fa-bars text-xl"></i></button>
                <h2 class="text-xl font-bold text-gray-800">Minhas Consultas</h2>
                <a href="{{ route('patient.schedule') }}" class="hidden md:inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm">
                    <i class="fas fa-plus"></i> Nova Consulta
                </a>
            </div>
        </header>

        <main class="p-6">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 flex items-center gap-3">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">📅 Minhas Consultas</h1>
                <p class="text-gray-600">Acompanhe todas as suas consultas médicas.</p>
            </div>

            <!-- Filtros -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <a href="{{ route('patient.consultations', ['filter' => 'all']) }}" class="bg-white p-4 rounded-xl shadow-sm border hover:shadow-md transition {{ $filter === 'all' ? 'ring-2 ring-emerald-500' : '' }}">
                    <p class="text-xs text-gray-500 uppercase font-semibold">Total</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
                </a>
                <a href="{{ route('patient.consultations', ['filter' => 'upcoming']) }}" class="bg-white p-4 rounded-xl shadow-sm border hover:shadow-md transition {{ $filter === 'upcoming' ? 'ring-2 ring-blue-500' : '' }}">
                    <p class="text-xs text-blue-600 uppercase font-semibold">Próximas</p>
                    <p class="text-2xl font-bold text-blue-700 mt-1">{{ $stats['upcoming'] }}</p>
                </a>
                <a href="{{ route('patient.consultations', ['filter' => 'past']) }}" class="bg-white p-4 rounded-xl shadow-sm border hover:shadow-md transition {{ $filter === 'past' ? 'ring-2 ring-green-500' : '' }}">
                    <p class="text-xs text-green-600 uppercase font-semibold">Concluídas</p>
                    <p class="text-2xl font-bold text-green-700 mt-1">{{ $stats['past'] }}</p>
                </a>
                <a href="{{ route('patient.consultations', ['filter' => 'cancelled']) }}" class="bg-white p-4 rounded-xl shadow-sm border hover:shadow-md transition {{ $filter === 'cancelled' ? 'ring-2 ring-red-500' : '' }}">
                    <p class="text-xs text-red-600 uppercase font-semibold">Canceladas</p>
                    <p class="text-2xl font-bold text-red-700 mt-1">{{ $stats['cancelled'] }}</p>
                </a>
            </div>

            <!-- Lista -->
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                @if($consultations->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($consultations as $consultation)
                            @php
                                $statusClass = match($consultation->status) {
                                    'agendada' => 'bg-blue-100 text-blue-800',
                                    'confirmada' => 'bg-indigo-100 text-indigo-800',
                                    'em_andamento' => 'bg-amber-100 text-amber-800',
                                    'concluida' => 'bg-green-100 text-green-800',
                                    'cancelada' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <div class="p-5 hover:bg-gray-50 transition">
                                <div class="flex flex-col md:flex-row md:items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-14 h-14 rounded-xl flex items-center justify-center text-3xl
                                            {{ $consultation->type === 'teleconsulta' ? 'bg-purple-100' : ($consultation->type === 'domicilio' ? 'bg-amber-100' : 'bg-blue-100') }}">
                                            {{ $consultation->type === 'presencial' ? '🏥' : ($consultation->type === 'teleconsulta' ? '💻' : '') }}
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between flex-wrap gap-2 mb-2">
                                            <div>
                                                <h3 class="font-bold text-gray-900 text-lg">
                                                    {{ $consultation->scheduled_at->format('d/m/Y \à\s H:i') }}
                                                </h3>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    <i class="fas fa-user-md text-emerald-600 mr-1"></i>
                                                    <strong>{{ $consultation->doctor->name ?? 'Médico' }}</strong>
                                                </p>
                                            </div>
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                                {{ ucfirst(str_replace('_', ' ', $consultation->status)) }}
                                            </span>
                                        </div>

                                        <p class="text-sm text-gray-600">
                                            {{ ucfirst($consultation->type) }}
                                            @if($consultation->insurance)
                                                • {{ $consultation->insurance->name }}
                                            @endif
                                        </p>

                                        @if($consultation->clinical_notes)
                                            <div class="mt-2 p-2 bg-gray-50 border border-gray-200 rounded-lg">
                                                <p class="text-xs text-gray-600"><strong>Queixa:</strong> {{ $consultation->clinical_notes }}</p>
                                            </div>
                                        @endif

                                        <!-- Botões de Ação -->
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            @if($consultation->type === 'teleconsulta')
                                                @if($consultation->isVideoCallActive())
                                                    <a href="{{ $consultation->location }}" target="_blank"
                                                       class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg">
                                                        <i class="fas fa-video"></i> Entrar na Videochamada
                                                    </a>
                                                @else
                                                    <a href="{{ route('patient.consultations.show', $consultation->id) }}"
                                                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg">
                                                        <i class="fas fa-info-circle"></i> Ver Credenciais
                                                    </a>
                                                @endif
                                            @endif

                                            @if(in_array($consultation->status, ['agendada', 'confirmada']))
                                                <form method="POST" action="{{ route('patient.consultations.cancel', $consultation->id) }}" 
                                                      onsubmit="return confirm('Tem certeza que deseja cancelar esta consulta?');"
                                                      class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold rounded-lg">
                                                        <i class="fas fa-times"></i> Cancelar
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($consultations->hasPages())
                        <div class="px-6 py-4 border-t bg-gray-50">
                            {{ $consultations->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <i class="fas fa-calendar-check text-6xl text-gray-300 mb-4"></i>
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">Nenhuma consulta encontrada</h4>
                        <p class="text-gray-500 mb-4">
                            @if($filter === 'upcoming')
                                Agende uma nova consulta para começar.
                            @else
                                Não há consultas para este filtro.
                            @endif
                        </p>
                        <a href="{{ route('patient.schedule') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl">
                            <i class="fas fa-calendar-plus"></i> Agendar Consulta
                        </a>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebar-overlay').classList.toggle('hidden');
        }
    </script>
</body>
</html>