<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard • Portal do Paciente - Makombe</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
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
        .nav-item:hover { background-color: rgba(255, 255, 255, 0.1); color: #ffffff; border-left-color: #a78bfa; }
        .nav-item.active { background-color: rgba(255, 255, 255, 0.15); color: #ffffff; border-left-color: #ffffff; font-weight: 700; }
        .nav-item i { width: 24px; text-align: center; margin-right: 12px; font-size: 1.1rem; }
        .main-content { margin-left: 270px; min-height: 100vh; background-color: #f8fafc; }
        
        @media (max-width: 768px) { 
            .sidebar { transform: translateX(-100%); } 
            .sidebar.open { transform: translateX(0); } 
            .main-content { margin-left: 0; } 
        }

        /* Animações suaves */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

    {{-- Sidebar Reutilizável --}}
    <x-portal-sidebar />

    <div class="main-content">
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-violet-700 text-xl" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Dashboard</h2>
                    <p class="text-xs text-slate-500 hidden sm:block">Visão geral da sua saúde e consultas</p>
                </div>
            </div>
            <div class="text-sm text-slate-500 hidden sm:flex items-center gap-2 bg-slate-100 px-3 py-1.5 rounded-lg">
                <i class="far fa-calendar-alt text-violet-600"></i> 
                {{ now()->locale('pt_PT')->isoFormat('DD [de] MMMM [de] YYYY') }}
            </div>
        </header>

        <main class="p-6 md:p-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 flex items-center gap-3 shadow-sm animate-fade-in">
                    <i class="fas fa-check-circle text-xl"></i> {{ session('success') }}
                </div>
            @endif

            {{-- 1. BANNER DE BOAS-VINDAS E AÇÃO RÁPIDA --}}
            <div class="bg-gradient-to-r from-violet-600 to-indigo-700 rounded-2xl p-6 md:p-8 text-white shadow-lg relative overflow-hidden animate-fade-in">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-16 -mt-16"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full -ml-12 -mb-12"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h1 class="text-3xl font-black mb-2">Olá, {{ explode(' ', $patient->full_name)[0] }}! 👋</h1>
                        <p class="text-violet-100 text-lg max-w-xl">
                            @if($stats['consultas_agendadas'] > 0)
                                Você tem <strong class="text-white">{{ $stats['consultas_agendadas'] }} consulta(s)</strong> agendada(s). Cuide da sua saúde!
                            @else
                                Aproveite o dia! Agende sua próxima consulta para manter o acompanhamento em dia.
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('patient.schedule') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-violet-700 font-bold rounded-xl shadow-md hover:bg-violet-50 transition transform hover:scale-105">
                        <i class="fas fa-calendar-plus"></i>
                        <span>Agendar Nova Consulta</span>
                    </a>
                </div>
            </div>

            {{-- 2. ESTATÍSTICAS RÁPIDAS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 animate-fade-in delay-100">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center group-hover:bg-blue-100 transition">
                            <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase">Total</span>
                    </div>
                    <p class="text-3xl font-black text-slate-800">{{ $stats['total_consultas'] }}</p>
                    <p class="text-sm text-slate-500 mt-1">Consultas realizadas</p>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center group-hover:bg-amber-100 transition">
                            <i class="fas fa-clock text-amber-600 text-xl"></i>
                        </div>
                        <span class="text-xs font-bold text-amber-600 uppercase">Pendentes</span>
                    </div>
                    <p class="text-3xl font-black text-slate-800">{{ $stats['consultas_agendadas'] }}</p>
                    <p class="text-sm text-slate-500 mt-1">Consultas agendadas</p>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center group-hover:bg-emerald-100 transition">
                            <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                        </div>
                        <span class="text-xs font-bold text-emerald-600 uppercase">Concluídas</span>
                    </div>
                    <p class="text-3xl font-black text-slate-800">{{ $stats['consultas_concluidas'] }}</p>
                    <p class="text-sm text-slate-500 mt-1">Atendimentos finalizados</p>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-violet-50 rounded-xl flex items-center justify-center group-hover:bg-violet-100 transition">
                            <i class="fas fa-file-invoice text-violet-600 text-xl"></i>
                        </div>
                        <span class="text-xs font-bold text-violet-600 uppercase">Financeiro</span>
                    </div>
                    <p class="text-2xl font-black text-slate-800">{{ number_format($stats['total_pago'] ?? 0, 0, ',', '.') }} <span class="text-sm font-medium text-slate-500">MT</span></p>
                    <p class="text-sm text-slate-500 mt-1">Total já pago</p>
                </div>
            </div>

            {{-- 3. CONTEÚDO PRINCIPAL (2 Colunas) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in delay-200">
                
                {{-- Coluna Esquerda (Larga): Próximas Consultas e Histórico --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Próxima Consulta em Destaque --}}
                    @if($upcomingConsultations->count() > 0)
                        @php $nextConsult = $upcomingConsultations->first(); @endphp
                        <div class="bg-white rounded-2xl shadow-sm border border-violet-100 overflow-hidden">
                            <div class="bg-violet-50 px-6 py-4 border-b border-violet-100 flex items-center justify-between">
                                <h3 class="font-bold text-violet-900 flex items-center gap-2">
                                    <i class="fas fa-star text-violet-600"></i> Próxima Consulta
                                </h3>
                                @if($nextConsult->scheduled_at->isToday())
                                    <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full animate-pulse">É HOJE!</span>
                                @else
                                    <span class="px-3 py-1 bg-violet-100 text-violet-700 text-xs font-bold rounded-full">Em breve</span>
                                @endif
                            </div>
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row md:items-center gap-6">
                                    <div class="flex-shrink-0 w-16 h-16 bg-violet-100 rounded-2xl flex items-center justify-center">
                                        @if($nextConsult->type === 'teleconsulta')
                                            <i class="fas fa-video text-violet-600 text-2xl"></i>
                                        @elseif($nextConsult->type === 'domicilio')
                                            <i class="fas fa-house-medical text-violet-600 text-2xl"></i>
                                        @else
                                            <i class="fas fa-stethoscope text-violet-600 text-2xl"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-2xl font-black text-slate-800 mb-1">
                                            {{ $nextConsult->scheduled_at->locale('pt_PT')->isoFormat('dddd, DD [de] MMMM [às] HH:mm') }}
                                        </p>
                                        <p class="text-slate-600 mb-3">
                                            <i class="fas fa-user-md text-violet-500 mr-2"></i> {{ $nextConsult->doctor->name ?? 'Médico(a)' }} 
                                            <span class="mx-2 text-slate-300">|</span>
                                            {{ ucfirst($nextConsult->type) }}
                                        </p>
                                        
                                        {{-- Botão de Ação Específico --}}
                                        @if($nextConsult->type === 'teleconsulta' && $nextConsult->location)
                                            <a href="{{ $nextConsult->location }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition shadow-md">
                                                <i class="fas fa-video"></i> Entrar na Videochamada
                                            </a>
                                        @else
                                            <a href="{{ route('patient.consultations.show', $nextConsult->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-lg transition shadow-md">
                                                <i class="fas fa-eye"></i> Ver Detalhes da Consulta
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Histórico Recente --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                <i class="fas fa-history text-slate-500"></i> Histórico de Consultas
                            </h3>
                            <a href="{{ route('patient.consultations', ['filter' => 'past']) }}" class="text-sm text-violet-600 hover:text-violet-800 font-medium hover:underline">Ver todas</a>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @forelse($pastConsultations->take(5) as $consultation)
                                <div class="p-4 hover:bg-slate-50 transition flex items-start gap-4">
                                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mt-1">
                                        <i class="fas fa-check-circle text-emerald-600"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between flex-wrap gap-2">
                                            <p class="font-bold text-slate-800 text-sm">
                                                {{ $consultation->scheduled_at->locale('pt_PT')->isoFormat('DD [de] MMMM [de] YYYY') }}
                                            </p>
                                            <span class="px-2 py-0.5 text-xs font-semibold bg-slate-100 text-slate-600 rounded-full">
                                                {{ ucfirst($consultation->type) }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-600 mt-1">
                                            <i class="fas fa-user-md mr-1 text-slate-400"></i> {{ $consultation->doctor->name ?? 'Médico(a)' }}
                                        </p>
                                        @if($consultation->diagnosis)
                                            <p class="text-xs text-slate-500 mt-2 bg-slate-50 p-2 rounded border border-slate-100 line-clamp-2">
                                                <strong class="text-slate-700">Diagnóstico:</strong> {{ Str::limit($consultation->diagnosis, 80) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="p-10 text-center text-slate-500">
                                    <i class="fas fa-folder-open text-4xl text-slate-300 mb-3"></i>
                                    <p class="font-medium">Nenhuma consulta concluída ainda.</p>
                                    <p class="text-sm mt-1">O seu histórico aparecerá aqui após os atendimentos.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Coluna Direita (Estreita): Resumo e Atalhos --}}
                <div class="space-y-6">
                    
                    {{-- Resumo de Saúde Rápido --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-heartbeat text-red-500"></i> O Meu Perfil de Saúde
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-red-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase">Alergias / Histórico</p>
                                    <p class="text-sm text-slate-800 font-medium mt-1">
                                        {{ Str::limit($patient->medical_history ?: 'Nenhum registo médico adicionado.', 60) }}
                                    </p>
                                    <a href="{{ route('patient.profile') }}" class="text-xs text-violet-600 hover:underline mt-1 inline-block">Atualizar informações →</a>
                                </div>
                            </div>
                            
                            <div class="border-t border-slate-100 pt-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-bold text-slate-500 uppercase">Seguradora Ativa</span>
                                </div>
                                @php $activeInsurance = $patient->insurances()->wherePivot('is_active', true)->first(); @endphp
                                @if($activeInsurance)
                                    <div class="flex items-center gap-2 bg-blue-50 p-3 rounded-lg border border-blue-100">
                                        <i class="fas fa-shield-alt text-blue-600"></i>
                                        <span class="text-sm font-bold text-blue-900">{{ $activeInsurance->name }}</span>
                                    </div>
                                @else
                                    <p class="text-sm text-slate-500 italic">Nenhuma seguradora vinculada.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Ações Rápidas --}}
                    <div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-2xl shadow-lg p-6 text-white">
                        <h3 class="font-bold mb-4 flex items-center gap-2">
                            <i class="fas fa-bolt text-yellow-300"></i> Ações Rápidas
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ route('patient.quotes') }}" class="flex items-center gap-3 p-3 bg-white/10 hover:bg-white/20 rounded-xl transition group">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-sm">Ver Cotações</p>
                                    <p class="text-xs text-indigo-200">{{ $stats['total_cotacoes'] ?? 0 }} registos</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('patient.payments') }}" class="flex items-center gap-3 p-3 bg-white/10 hover:bg-white/20 rounded-xl transition group">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-sm">Meus Pagamentos</p>
                                    <p class="text-xs text-indigo-200">Histórico financeiro</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Dica de Saúde --}}
                    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-lightbulb text-emerald-600 text-xl mt-1"></i>
                            <div>
                                <p class="font-bold text-emerald-900 text-sm mb-1">Dica de Saúde do Dia</p>
                                <p class="text-sm text-emerald-800 leading-relaxed">
                                    Beba pelo menos 2 litros de água por dia e tente fazer 30 minutos de atividade física leve. A sua saúde agradece! 💧
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
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