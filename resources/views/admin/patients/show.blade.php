<x-layouts.admin title="Paciente: {{ $patient->full_name }}">

    <!-- Botões de Ação -->
    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('patients.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
        <span class="text-gray-300">|</span>
        <a href="{{ route('patients.edit', $patient->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition text-sm font-medium">
            <i class="fas fa-edit"></i> Editar
        </a>
        <form method="POST" action="{{ route('patients.toggleStatus', $patient->id) }}" class="inline">
            @csrf
            <button type="submit" 
                    onclick="return confirm('{{ $patient->is_active ? 'Desativar' : 'Ativar' }} este paciente?');"
                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition text-sm font-medium">
                <i class="fas fa-{{ $patient->is_active ? 'ban' : 'check' }}"></i>
                {{ $patient->is_active ? 'Desativar' : 'Ativar' }}
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Coluna Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card de Perfil -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
                    <div class="flex items-center gap-6 relative z-10">
                        <div class="h-24 w-24 rounded-full bg-white flex items-center justify-center shadow-xl overflow-hidden">
                            @if($patient->hasPhoto())
                                <img src="{{ $patient->getPhotoUrl() }}" alt="{{ $patient->full_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-4xl font-bold">
                                    {{ strtoupper(substr($patient->full_name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold mb-1">{{ $patient->full_name }}</h1>
                            <p class="text-blue-100 text-sm">
                                <i class="fas fa-id-card mr-1"></i> NID: <span class="font-mono font-bold">{{ $patient->nid }}</span>
                            </p>
                            <div class="mt-2 inline-block px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-semibold">
                                {{ $patient->is_active ? '✅ Ativo' : '❌ Inativo' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Email</p>
                            <p class="text-gray-900 flex items-center gap-2">
                                <i class="fas fa-envelope text-blue-600"></i>
                                <a href="mailto:{{ $patient->email }}" class="hover:text-blue-600">{{ $patient->email }}</a>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Telefone</p>
                            <p class="text-gray-900 flex items-center gap-2">
                                <i class="fas fa-phone text-blue-600"></i>
                                <a href="tel:+258{{ $patient->phone }}" class="hover:text-blue-600">+258 {{ $patient->phone }}</a>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Data de Nascimento</p>
                            <p class="text-gray-900 flex items-center gap-2">
                                <i class="fas fa-birthday-cake text-blue-600"></i>
                                {{ $patient->birth_date?->format('d/m/Y') ?? '-' }}
                                @if($patient->birth_date)
                                    <span class="text-xs text-gray-500">({{ $patient->birth_date->age }} anos)</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Género</p>
                            <p class="text-gray-900 flex items-center gap-2">
                                @php
                                    $genderIcon = match($patient->gender) {
                                        'masculino' => '♂',
                                        'feminino' => '♀',
                                        default => '⚧',
                                    };
                                @endphp
                                <span class="text-xl">{{ $genderIcon }}</span>
                                {{ ucfirst($patient->gender) }}
                            </p>
                        </div>
                        @if($patient->bi_number)
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">BI</p>
                                <p class="text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-fingerprint text-blue-600"></i>
                                    {{ $patient->bi_number }}
                                </p>
                            </div>
                        @endif
                        @if($patient->address)
                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Endereço</p>
                                <p class="text-gray-900 flex items-start gap-2">
                                    <i class="fas fa-map-marker-alt text-blue-600 mt-1"></i>
                                    <span>{{ $patient->address }}</span>
                                </p>
                            </div>
                        @endif
                    </div>

                    @if($patient->medical_history)
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-2">
                                <i class="fas fa-notes-medical text-amber-600 mr-1"></i> Histórico Médico
                            </p>
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-amber-900">
                                {{ $patient->medical_history }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Consultas Recentes -->
            @if($recentConsultations->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-calendar-check text-blue-600"></i>
                            Consultas Recentes
                        </h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($recentConsultations as $consultation)
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-stethoscope text-blue-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900 text-sm">
                                            {{ $consultation->scheduled_at->format('d/m/Y \à\s H:i') }}
                                        </p>
                                        <p class="text-xs text-gray-600">
                                            Dr(a). {{ $consultation->doctor->name ?? '-' }} • 
                                            {{ $consultation->type === 'presencial' ? '🏥' : ($consultation->type === 'teleconsulta' ? '💻' : '🏠') }}
                                            {{ ucfirst($consultation->type) }}
                                        </p>
                                        <span class="inline-block mt-1 px-2 py-0.5 text-xs font-semibold rounded-full {{ $consultation->getStatusBadgeClass() }}">
                                            {{ $consultation->getStatusLabel() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Coluna Lateral -->
        <div class="space-y-6">
            
            <!-- Informações do Sistema -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i> Informações do Sistema
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Criado em:</span>
                        <span class="font-medium text-gray-900">{{ $patient->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Última atualização:</span>
                        <span class="font-medium text-gray-900">{{ $patient->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID:</span>
                        <span class="font-mono text-gray-900">#{{ $patient->id }}</span>
                    </div>
                    @if($patient->createdBy)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Criado por:</span>
                            <span class="font-medium text-gray-900">{{ $patient->createdBy->name }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-bolt text-yellow-500"></i> Ações Rápidas
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('patients.edit', $patient->id) }}" 
                       class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">Editar Paciente</p>
                            <p class="text-xs text-gray-500">Alterar dados</p>
                        </div>
                    </a>
                    
                    <form method="POST" action="{{ route('patients.toggleStatus', $patient->id) }}">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('{{ $patient->is_active ? 'Desativar' : 'Ativar' }} este paciente?');"
                                class="w-full flex items-center gap-3 p-3 bg-amber-50 hover:bg-amber-100 rounded-lg transition text-left">
                            <div class="w-10 h-10 bg-amber-600 rounded-lg flex items-center justify-center text-white">
                                <i class="fas fa-{{ $patient->is_active ? 'ban' : 'check' }}"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $patient->is_active ? 'Desativar' : 'Ativar' }} Conta</p>
                                <p class="text-xs text-gray-500">Bloquear ou liberar acesso</p>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>