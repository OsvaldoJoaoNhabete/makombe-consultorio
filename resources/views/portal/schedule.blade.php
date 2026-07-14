<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Consulta • Portal do Paciente - Makombe</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Lato', sans-serif; }
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 270px; background: linear-gradient(180deg, #4c1d95 0%, #5b21b6 50%, #6d28d9 100%); display: flex; flex-direction: column; z-index: 50; transition: transform 0.3s ease; }
        .nav-item { display: flex; align-items: center; padding: 0.85rem 1.5rem; color: #ddd6fe; text-decoration: none; transition: all 0.2s ease; font-size: 0.95rem; font-weight: 500; border-left: 4px solid transparent; }
        .nav-item:hover { background-color: rgba(255, 255, 255, 0.1); color: #ffffff; border-left-color: #a78bfa; }
        .nav-item.active { background-color: rgba(255, 255, 255, 0.15); color: #ffffff; border-left-color: #ffffff; font-weight: 700; }
        .nav-item i { width: 24px; text-align: center; margin-right: 12px; font-size: 1.1rem; }
        .main-content { margin-left: 270px; min-height: 100vh; background-color: #f8fafc; }
        @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .sidebar.open { transform: translateX(0); } .main-content { margin-left: 0; } }
        
        /* Estilo para seleção de tipo de consulta */
        .type-radio:checked + div { border-color: #7c3aed; background-color: #f5f3ff; }
        .type-radio:checked + div .check-icon { opacity: 1; transform: scale(1); }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden md:hidden" onclick="toggleSidebar()"></div>
    <x-portal-sidebar />

    <div class="main-content">
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-violet-700 text-xl" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Agendar Consulta</h2>
                    <p class="text-xs text-slate-500">Preencha os dados para solicitar o seu atendimento</p>
                </div>
            </div>
        </header>

        <main class="p-6 md:p-8 max-w-5xl mx-auto">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 flex items-center gap-3">
                    <i class="fas fa-check-circle text-xl"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <ul class="text-sm text-red-800 space-y-1">
                        @foreach($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('patient.schedule.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Coluna Esquerda: Formulário -->
                    <div class="lg:col-span-2 space-y-6">
                        
                        <!-- 1. Tipo de Consulta -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 bg-violet-100 text-violet-700 rounded-full flex items-center justify-center text-sm font-black">1</span>
                                Tipo de Atendimento
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="type" value="presencial" class="type-radio sr-only" checked>
                                    <div class="border-2 border-slate-200 rounded-xl p-4 hover:border-violet-300 transition text-center h-full">
                                        <div class="check-icon absolute top-3 right-3 w-6 h-6 bg-violet-600 text-white rounded-full flex items-center justify-center opacity-0 transform scale-50 transition-all">
                                            <i class="fas fa-check text-xs"></i>
                                        </div>
                                        <i class="fas fa-hospital text-3xl text-violet-600 mb-3"></i>
                                        <p class="font-bold text-slate-800">Presencial</p>
                                        <p class="text-xs text-slate-500 mt-1">No consultório</p>
                                    </div>
                                </label>

                                <label class="cursor-pointer relative">
                                    <input type="radio" name="type" value="teleconsulta" class="type-radio sr-only">
                                    <div class="border-2 border-slate-200 rounded-xl p-4 hover:border-violet-300 transition text-center h-full">
                                        <div class="check-icon absolute top-3 right-3 w-6 h-6 bg-violet-600 text-white rounded-full flex items-center justify-center opacity-0 transform scale-50 transition-all">
                                            <i class="fas fa-check text-xs"></i>
                                        </div>
                                        <i class="fas fa-video text-3xl text-violet-600 mb-3"></i>
                                        <p class="font-bold text-slate-800">Teleconsulta</p>
                                        <p class="text-xs text-slate-500 mt-1">Por videochamada</p>
                                    </div>
                                </label>

                                <label class="cursor-pointer relative">
                                    <input type="radio" name="type" value="domicilio" class="type-radio sr-only">
                                    <div class="border-2 border-slate-200 rounded-xl p-4 hover:border-violet-300 transition text-center h-full">
                                        <div class="check-icon absolute top-3 right-3 w-6 h-6 bg-violet-600 text-white rounded-full flex items-center justify-center opacity-0 transform scale-50 transition-all">
                                            <i class="fas fa-check text-xs"></i>
                                        </div>
                                        <i class="fas fa-house-medical text-3xl text-violet-600 mb-3"></i>
                                        <p class="font-bold text-slate-800">Domicílio</p>
                                        <p class="text-xs text-slate-500 mt-1">Na sua casa</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- 2. Data e Hora -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 bg-violet-100 text-violet-700 rounded-full flex items-center justify-center text-sm font-black">2</span>
                                Data e Horário
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Data da Consulta *</label>
                                    <!-- MIN alterado para hoje (date('Y-m-d')) -->
                                    <input type="date" name="date" required min="{{ date('Y-m-d') }}"
                                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition">
                                    <p class="text-xs text-slate-500 mt-1"><i class="fas fa-info-circle mr-1"></i>Pode agendar para hoje ou dias futuros.</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Horário Preferencial *</label>
                                    <input type="time" name="time" required min="07:00" max="18:00"
                                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition">
                                    <p class="text-xs text-slate-500 mt-1"><i class="fas fa-clock mr-1"></i>Atendimento das 07:00 às 19:00.</p>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Detalhes Adicionais -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 bg-violet-100 text-violet-700 rounded-full flex items-center justify-center text-sm font-black">3</span>
                                Detalhes Adicionais
                            </h3>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Seguradora (Opcional)</label>
                                <select name="insurance_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500 bg-white">
                                    <option value="">Nenhuma (Particular)</option>
                                    @foreach($insurances as $insurance)
                                        <option value="{{ $insurance->id }}">{{ $insurance->name }} ({{ $insurance->coverage_percentage }}% cobertura)</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Motivo da Consulta / Sintomas</label>
                                <textarea name="clinical_notes" rows="4" 
                                          class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500 transition"
                                          placeholder="Descreva brevemente o que está a sentir ou o motivo do agendamento..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Coluna Direita: Resumo e Info -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-24 space-y-6">
                            
                            <!-- Card de Informação -->
                            <div class="bg-violet-50 border border-violet-200 rounded-2xl p-5">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-lightbulb text-violet-600 text-xl mt-1"></i>
                                    <div>
                                        <h4 class="font-bold text-violet-900 text-sm mb-1">Como funciona?</h4>
                                        <p class="text-sm text-violet-800 leading-relaxed">
                                            Ao enviar este pedido, a nossa equipe de receção irá revisar a disponibilidade e <strong>atribuir o médico especialista</strong> mais adequado para o seu caso. Você receberá a confirmação por SMS/WhatsApp.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Resumo do Paciente -->
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                                <h4 class="font-bold text-slate-800 mb-3 text-sm uppercase tracking-wide">Resumo do Agendamento</h4>
                                <div class="space-y-3 text-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-user text-slate-500"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Paciente</p>
                                            <p class="font-semibold text-slate-800">{{ $patient->full_name }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-phone text-slate-500"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Contacto</p>
                                            <p class="font-semibold text-slate-800">+258 {{ $patient->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botão de Ação -->
                            <button type="submit" 
                                    class="w-full py-4 px-6 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg transition transform hover:scale-[1.02] flex items-center justify-center gap-2 text-lg">
                                <i class="fas fa-paper-plane"></i>
                                <span>Solicitar Agendamento</span>
                            </button>
                            
                            <a href="{{ route('patient.dashboard') }}" class="block text-center text-sm text-slate-500 hover:text-violet-600 transition">
                                <i class="fas fa-arrow-left mr-1"></i> Voltar ao Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </form>
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