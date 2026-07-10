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
            <a href="{{ route('patient.schedule') }}" class="nav-item active"><i class="fas fa-calendar-plus w-6"></i> Agendar Consulta</a>
            <a href="{{ route('patient.consultations') }}" class="nav-item"><i class="fas fa-calendar-check w-6"></i> Minhas Consultas</a>
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
                <h2 class="text-xl font-bold text-gray-800">Agendar Consulta</h2>
                <div></div>
            </div>
        </header>

        <main class="p-6">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 flex items-center gap-3">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <div class="mb-6 pb-6 border-b">
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">📅 Agendar Nova Consulta</h1>
                        <p class="text-gray-600">Preencha os dados abaixo para agendar sua consulta.</p>
                    </div>

                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                            <ul class="text-sm text-red-800 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('patient.schedule.store') }}" class="space-y-6">
                        @csrf

                        <!-- Tipo de Consulta -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-stethoscope text-emerald-600 mr-1"></i> Tipo de Consulta <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="presencial" required class="peer hidden" checked>
                                    <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition text-center">
                                        <div class="text-3xl mb-2">🏥</div>
                                        <p class="font-semibold text-gray-900">Presencial</p>
                                        <p class="text-xs text-gray-500">No consultório</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="teleconsulta" class="peer hidden">
                                    <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition text-center">
                                        <div class="text-3xl mb-2">💻</div>
                                        <p class="font-semibold text-gray-900">Teleconsulta</p>
                                        <p class="text-xs text-gray-500">Online por vídeo</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="domicilio" class="peer hidden">
                                    <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition text-center">
                                        <div class="text-3xl mb-2">🏠</div>
                                        <p class="font-semibold text-gray-900">Domicílio</p>
                                        <p class="text-xs text-gray-500">Em sua casa</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Médico -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user-md text-emerald-600 mr-1"></i> Médico <span class="text-red-500">*</span>
                            </label>
                            <select name="doctor_id" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="">Selecione um médico...</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Data e Hora -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar text-emerald-600 mr-1"></i> Data <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="date" id="consultationDate" required 
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    onchange="updateWeekday()"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                
                                <!-- Dia da Semana (aparece dinamicamente) -->
                                <div id="weekdayDisplay" class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg hidden">
                                    <p class="text-sm text-blue-800 font-semibold">
                                        <i class="fas fa-calendar-day mr-1"></i>
                                        <span id="weekdayText"></span>
                                    </p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-clock text-emerald-600 mr-1"></i> Horário <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="time" required min="07:00" max="18:00"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <p class="text-xs text-gray-500 mt-1"><i class="fas fa-info-circle mr-1"></i>Horário de atendimento: 07:00 - 19:00</p>
                            </div>
                        </div>

                        <!-- Seguradora -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-shield-alt text-emerald-600 mr-1"></i> Seguradora (Opcional)
                            </label>
                            <select name="insurance_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="">Nenhuma (particular)</option>
                                @foreach($insurances as $insurance)
                                    <option value="{{ $insurance->id }}">{{ $insurance->name }} ({{ $insurance->coverage_percentage }}% cobertura)</option>
                                @endforeach
                            </select>
                            @if($patientInsurances->count() > 0)
                                <p class="text-xs text-gray-500 mt-2"><i class="fas fa-info-circle mr-1"></i>Você tem {{ $patientInsurances->count() }} seguradora(s) vinculada(s).</p>
                            @endif
                        </div>

                        <!-- Queixa Principal -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-comment-medical text-emerald-600 mr-1"></i> Queixa Principal
                            </label>
                            <textarea name="clinical_notes" rows="4" placeholder="Descreva brevemente o motivo da consulta..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                        </div>

                        <!-- Informações -->
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <h4 class="font-semibold text-blue-900 mb-2 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i> Informações Importantes
                            </h4>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• Chegue com 15 minutos de antecedência para consultas presenciais</li>
                                <li>• Para teleconsultas, o link será enviado por email/WhatsApp</li>
                                <li>• Pode cancelar ou reagendar até 24h antes</li>
                                <li>• Traga documentos e exames anteriores</li>
                            </ul>
                        </div>

                        <!-- Botões -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
                            <button type="submit" class="flex-1 py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition flex items-center justify-center gap-2">
                                <i class="fas fa-calendar-check"></i> Confirmar Agendamento
                            </button>
                            <a href="{{ route('patient.dashboard') }}" class="flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition flex items-center justify-center gap-2">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebar-overlay').classList.toggle('hidden');
    }

    // Mostrar dia da semana ao selecionar data
    function updateWeekday() {
        const dateInput = document.getElementById('consultationDate');
        const weekdayDisplay = document.getElementById('weekdayDisplay');
        const weekdayText = document.getElementById('weekdayText');
        
        if (dateInput.value) {
            const date = new Date(dateInput.value + 'T00:00:00');
            const weekdays = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
            const weekday = weekdays[date.getDay()];
            
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            
            weekdayText.textContent = `${weekday}, ${day}/${month}/${year}`;
            weekdayDisplay.classList.remove('hidden');
            
            // Verificar se é fim de semana
            if (date.getDay() === 0 || date.getDay() === 6) {
                weekdayDisplay.classList.remove('bg-blue-50', 'border-blue-200');
                weekdayDisplay.classList.add('bg-amber-50', 'border-amber-200');
                weekdayText.innerHTML = `<i class="fas fa-exclamation-triangle mr-1"></i> ${weekdayText.textContent} <span class="text-xs">(Fim de semana - consulte disponibilidade)</span>`;
            } else {
                weekdayDisplay.classList.remove('bg-amber-50', 'border-amber-200');
                weekdayDisplay.classList.add('bg-blue-50', 'border-blue-200');
            }
        } else {
            weekdayDisplay.classList.add('hidden');
        }
    }
</script>
</body>
</html>