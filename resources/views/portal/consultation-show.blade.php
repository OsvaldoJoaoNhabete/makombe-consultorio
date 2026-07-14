<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Consulta • Portal do Paciente - Makombe</title>
    
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
</style>
</head>
<body class="bg-gray-50">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

    <x-portal-sidebar />

    <div class="main-content">
        <header class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <button class="md:hidden text-gray-600" onclick="toggleSidebar()"><i class="fas fa-bars text-xl"></i></button>
                <h2 class="text-xl font-bold text-gray-800">Detalhes da Consulta</h2>
                <div></div>
            </div>
        </header>

        <main class="p-6">
            <div class="mb-4">
                <a href="{{ route('patient.consultations') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-arrow-left"></i> Voltar para consultas
                </a>
            </div>

            <div class="max-w-4xl mx-auto">
                
                <!-- Card Principal -->
                @php
                    $statusClass = match($consultation->status) {
                        'agendada' => 'from-blue-600 to-blue-700',
                        'confirmada' => 'from-indigo-600 to-indigo-700',
                        'em_andamento' => 'from-amber-600 to-amber-700',
                        'concluida' => 'from-green-600 to-green-700',
                        'cancelada' => 'from-red-600 to-red-700',
                        default => 'from-gray-600 to-gray-700',
                    };
                @endphp

                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <div class="bg-gradient-to-r {{ $statusClass }} px-6 py-8 text-white">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div>
                                <p class="text-sm opacity-80">Consulta #{{ $consultation->id }}</p>
                                <h1 class="text-3xl font-bold mt-1">
                                    {{ $consultation->scheduled_at->format('d/m/Y \à\s H:i') }}
                                </h1>
                                <p class="text-white/80 mt-2">
                                    @if($consultation->type === 'presencial') 🏥 Presencial
                                    @elseif($consultation->type === 'teleconsulta') 💻 Teleconsulta
                                    @else 🏠 Domicílio
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-semibold">
                                    {{ ucfirst(str_replace('_', ' ', $consultation->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">
                        
                        <!-- Médico -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Médico</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($consultation->doctor->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $consultation->doctor->name ?? 'Médico' }}</p>
                                        <p class="text-sm text-gray-600">{{ $consultation->doctor->email ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($consultation->insurance)
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Seguradora</p>
                                    <p class="font-medium text-gray-900">{{ $consultation->insurance->name }}</p>
                                    <p class="text-sm text-gray-600">Cobertura: {{ $consultation->insurance->getCoverageFormatted() }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Link da Videochamada (se teleconsulta) -->
                        @if($consultation->type === 'teleconsulta' && $consultation->location)
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 border-2 border-purple-200 rounded-xl p-6">
                                <h3 class="font-bold text-purple-900 mb-4 flex items-center gap-2">
                                    <i class="fas fa-video text-purple-600"></i> Credenciais da Videochamada
                                </h3>
                                
                                <div class="space-y-3">
                                    <div class="bg-white rounded-lg p-3 border border-purple-200">
                                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">
                                            <i class="fas fa-hashtag text-purple-600 mr-1"></i>ID da Sala
                                        </p>
                                        <p class="text-sm font-mono font-bold text-gray-900 break-all">
                                            {{ $consultation->getJitsiRoomId() }}
                                        </p>
                                    </div>

                                    <div class="bg-white rounded-lg p-3 border border-purple-200">
                                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">
                                            <i class="fas fa-link text-purple-600 mr-1"></i>Link de Acesso
                                        </p>
                                        <p class="text-sm font-mono text-gray-700 break-all mb-2">
                                            {{ $consultation->location }}
                                        </p>
                                        <button onclick="copyToClipboard('{{ $consultation->location }}')" 
                                                class="text-xs text-purple-600 hover:text-purple-800 font-semibold flex items-center gap-1">
                                            <i class="fas fa-copy"></i> Copiar Link
                                        </button>
                                    </div>
                                </div>

                                <!-- Botão Entrar -->
                                @if($consultation->isVideoCallActive())
                                    <a href="{{ $consultation->location }}" target="_blank"
                                       class="mt-4 block w-full py-3 px-4 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold rounded-xl text-center shadow-lg transition">
                                        <i class="fas fa-video mr-2"></i> ENTRAR NA VIDEOCHAMADA
                                    </a>
                                @else
                                    <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg text-center">
                                        <p class="text-sm text-amber-800">
                                            <i class="fas fa-clock mr-1"></i> 
                                            <strong>A videochamada ainda não foi iniciada pelo médico.</strong><br>
                                            Guarde estas credenciais e volte no horário marcado.
                                        </p>
                                    </div>
                                @endif

                                <!-- Download Jitsi -->
                                <div class="mt-4 pt-4 border-t border-purple-200">
                                    <p class="text-xs text-purple-800 font-semibold mb-2">
                                        <i class="fas fa-download mr-1"></i> Baixe o aplicativo Jitsi Meet:
                                    </p>
                                    <div class="grid grid-cols-3 gap-2">
                                        <a href="https://play.google.com/store/apps/details?id=org.jitsi.meet" target="_blank"
                                           class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg text-center transition">
                                            <i class="fab fa-android mr-1"></i> Android
                                        </a>
                                        <a href="https://apps.apple.com/app/jitsi-meet/id1165103905" target="_blank"
                                           class="px-3 py-2 bg-gray-800 hover:bg-gray-900 text-white text-xs font-semibold rounded-lg text-center transition">
                                            <i class="fab fa-apple mr-1"></i> iOS
                                        </a>
                                        <a href="https://github.com/jitsi/jitsi-meet-electron/releases" target="_blank"
                                           class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg text-center transition">
                                            <i class="fas fa-desktop mr-1"></i> Desktop
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Queixa Principal -->
                        @if($consultation->clinical_notes)
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">
                                    <i class="fas fa-comment-medical text-blue-600 mr-1"></i> Queixa Principal
                                </p>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-gray-800">
                                    {{ $consultation->clinical_notes }}
                                </div>
                            </div>
                        @endif

                        <!-- Diagnóstico (se concluída) -->
                        @if($consultation->diagnosis)
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">
                                    <i class="fas fa-stethoscope text-green-600 mr-1"></i> Diagnóstico
                                </p>
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-gray-800">
                                    {{ $consultation->diagnosis }}
                                </div>
                            </div>
                        @endif

                        <!-- Prescrição (se concluída) -->
                        @if($consultation->prescription)
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">
                                    <i class="fas fa-prescription text-purple-600 mr-1"></i> Prescrição
                                </p>
                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-sm text-gray-800 whitespace-pre-line font-mono">
                                    {{ $consultation->prescription }}
                                </div>
                            </div>
                        @endif

                        <!-- Ações -->
                        <div class="pt-6 border-t flex flex-wrap gap-2">
                            @if(in_array($consultation->status, ['agendada', 'confirmada']))
                                <form method="POST" action="{{ route('patient.consultations.cancel', $consultation->id) }}" 
                                      onsubmit="return confirm('Tem certeza que deseja cancelar esta consulta?');"
                                      class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                                        <i class="fas fa-times mr-1"></i> Cancelar Consulta
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('patient.consultations') }}" 
                               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition">
                                <i class="fas fa-arrow-left mr-1"></i> Voltar
                            </a>
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

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('✅ Link copiado!');
            });
        }
    </script>
</body>
</html>