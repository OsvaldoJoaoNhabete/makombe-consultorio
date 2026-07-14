<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil • Portal do Paciente - Makombe</title>
    
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
                <h2 class="text-xl font-bold text-gray-800">Meu Perfil</h2>
                <div></div>
            </div>
        </header>

        <main class="p-6">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 flex items-center gap-3">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">👤 Meu Perfil</h1>
                <p class="text-gray-600">Gerencie suas informações pessoais.</p>
            </div>

            <div class="max-w-4xl mx-auto">
                
                <!-- Card de Perfil -->
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-8 text-white">
                        <div class="flex items-center gap-6">
                            <div class="h-24 w-24 rounded-full bg-white flex items-center justify-center shadow-xl overflow-hidden">
                                @if($patient->hasPhoto())
                                    <img src="{{ $patient->getPhotoUrl() }}" alt="{{ $patient->full_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white text-4xl font-bold">
                                        {{ strtoupper(substr($patient->full_name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold mb-1">{{ $patient->full_name }}</h1>
                                <p class="text-emerald-100 text-sm">
                                    <i class="fas fa-id-card mr-1"></i> NID: <span class="font-mono font-bold">{{ $patient->nid }}</span>
                                </p>
                                <div class="mt-2 inline-block px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-semibold">
                                    {{ $patient->is_active ? '✅ Conta Ativa' : '❌ Conta Inativa' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        @if($errors->any())
                            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                                <ul class="text-sm text-red-800 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('patient.profile.update') }}" class="space-y-6">
                            @csrf

                            <!-- Informações Pessoais -->
                            <div class="border-b pb-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="fas fa-user text-emerald-600"></i> Informações Pessoais
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nome Completo</label>
                                        <input type="text" value="{{ $patient->full_name }}" readonly
                                               class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-600">
                                        <p class="text-xs text-gray-500 mt-1"><i class="fas fa-lock mr-1"></i>Não pode ser alterado</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                                        <input type="email" value="{{ $patient->email }}" readonly
                                               class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-600">
                                        <p class="text-xs text-gray-500 mt-1"><i class="fas fa-lock mr-1"></i>Não pode ser alterado</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Data de Nascimento</label>
                                        <input type="text" value="{{ $patient->birth_date?->format('d/m/Y') }}" readonly
                                               class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-600">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Género</label>
                                        <input type="text" value="{{ ucfirst($patient->gender) }}" readonly
                                               class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-600">
                                    </div>
                                </div>
                            </div>

                            <!-- Contacto -->
                            <div class="border-b pb-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="fas fa-phone text-emerald-600"></i> Contacto
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                                            Telemóvel <span class="text-red-500">*</span>
                                        </label>
                                        <div class="flex">
                                            <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm">+258</span>
                                            <input type="tel" name="phone" value="{{ old('phone', $patient->phone) }}" required maxlength="9"
                                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-r-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                                   placeholder="841234567">
                                        </div>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Endereço</label>
                                        <textarea name="address" rows="2" 
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                                  placeholder="Bairro, Rua, Número">{{ old('address', $patient->address) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Histórico Médico -->
                            <div class="border-b pb-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="fas fa-notes-medical text-amber-600"></i> Histórico Médico
                                </h3>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        Alergias, Condições Crônicas, Medicamentos em Uso
                                    </label>
                                    <textarea name="medical_history" rows="4" 
                                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                              placeholder="Ex: Alergia a penicilina, hipertensão controlada...">{{ old('medical_history', $patient->medical_history) }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Estas informações ajudam os médicos a prestar um melhor atendimento.
                                    </p>
                                </div>
                            </div>

                            <!-- Alterar Senha -->
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="fas fa-lock text-green-600"></i> Alterar Senha (Opcional)
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nova Senha</label>
                                        <input type="password" name="password" minlength="6"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                               placeholder="Deixe em branco para manter">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Confirmar Nova Senha</label>
                                        <input type="password" name="password_confirmation" minlength="6"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                               placeholder="Repita a nova senha">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Deixe em branco se não quiser alterar a senha.
                                </p>
                            </div>

                            <!-- Botões -->
                            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
                                <button type="submit" 
                                        class="flex-1 py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition flex items-center justify-center gap-2">
                                    <i class="fas fa-save"></i> Atualizar Perfil
                                </button>
                                <a href="{{ route('patient.dashboard') }}" 
                                   class="flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition flex items-center justify-center gap-2">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Informações do Sistema -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-600"></i> Informações da Conta
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Conta criada em:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $patient->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Última atualização:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $patient->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($patient->first_login_at)
                            <div>
                                <span class="text-gray-600">Primeiro acesso:</span>
                                <span class="font-medium text-green-600 ml-2">{{ $patient->first_login_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                        <div>
                            <span class="text-gray-600">BI:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $patient->bi_number ?? 'Não informado' }}</span>
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