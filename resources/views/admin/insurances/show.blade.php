<x-layouts.admin title="Seguradora: {{ $insurance->name }}">

    <!-- Ações -->
    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('insurances.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
        <span class="text-gray-300">|</span>
        <a href="{{ route('insurances.edit', $insurance->id) }}" 
           class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium">
            <i class="fas fa-edit"></i> Editar
        </a>
        <form method="POST" action="{{ route('insurances.toggleStatus', $insurance->id) }}" class="inline">
            @csrf
            <button type="submit" 
                    onclick="return confirm('{{ $insurance->is_active ? 'Desativar' : 'Ativar' }} esta seguradora?');"
                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg text-sm font-medium">
                <i class="fas fa-{{ $insurance->is_active ? 'ban' : 'check' }}"></i>
                {{ $insurance->is_active ? 'Desativar' : 'Ativar' }}
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Coluna Principal -->
        <div class="lg:col-span-2">
            
            <!-- Card da Seguradora -->
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
                    <div class="flex items-center gap-6 relative z-10">
                        <div class="h-24 w-24 rounded-xl bg-white flex items-center justify-center shadow-xl overflow-hidden flex-shrink-0">
                            @if($insurance->logo_path)
                                <img src="{{ $insurance->getLogoUrl() }}" alt="{{ $insurance->name }}" class="w-full h-full object-contain p-2">
                            @else
                                <i class="fas fa-shield-alt text-5xl text-blue-600"></i>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold mb-1">{{ $insurance->name }}</h1>
                            @if($insurance->code)
                                <p class="text-blue-100 text-sm font-mono">
                                    <i class="fas fa-hashtag mr-1"></i> Código: {{ $insurance->code }}
                                </p>
                            @endif
                            <div class="mt-2 inline-block px-3 py-1 {{ $insurance->is_active ? 'bg-green-500' : 'bg-red-500' }} rounded-full text-xs font-semibold">
                                {{ $insurance->is_active ? '✅ Ativa' : '❌ Inativa' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Cobertura -->
                    <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-purple-800 uppercase font-semibold">Percentagem de Cobertura</p>
                                <p class="text-3xl font-bold text-purple-900 mt-1">{{ number_format($insurance->coverage_percentage, 0) }}%</p>
                            </div>
                            <div class="w-20 h-20 relative">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="40" cy="40" r="35" fill="none" stroke="#e9d5ff" stroke-width="8"/>
                                    <circle cx="40" cy="40" r="35" fill="none" stroke="#9333ea" stroke-width="8"
                                            stroke-dasharray="{{ $insurance->coverage_percentage * 2.2 }} 220"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Informações de Contacto -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($insurance->contact_person)
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Pessoa de Contacto</p>
                                <p class="text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-user text-blue-600"></i>
                                    {{ $insurance->contact_person }}
                                </p>
                            </div>
                        @endif
                        @if($insurance->email)
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Email</p>
                                <p class="text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-envelope text-blue-600"></i>
                                    <a href="mailto:{{ $insurance->email }}" class="hover:text-blue-600">{{ $insurance->email }}</a>
                                </p>
                            </div>
                        @endif
                        @if($insurance->phone)
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Telefone</p>
                                <p class="text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-phone text-blue-600"></i>
                                    <a href="tel:{{ $insurance->phone }}" class="hover:text-blue-600">{{ $insurance->phone }}</a>
                                </p>
                            </div>
                        @endif
                        @if($insurance->address)
                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Endereço</p>
                                <p class="text-gray-900 flex items-start gap-2">
                                    <i class="fas fa-map-marker-alt text-blue-600 mt-1"></i>
                                    <span>{{ $insurance->address }}</span>
                                </p>
                            </div>
                        @endif
                    </div>

                    @if($insurance->notes)
                        <div class="mt-6 pt-6 border-t">
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-2">
                                <i class="fas fa-sticky-note text-amber-600 mr-1"></i> Observações
                            </p>
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-gray-800 whitespace-pre-line">
                                {{ $insurance->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pacientes Vinculados -->
            @if($linkedPatients->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border mt-6 overflow-hidden">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-users text-blue-600"></i>
                            Pacientes Vinculados (últimos 10)
                        </h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($linkedPatients as $patient)
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($patient->full_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm">{{ $patient->full_name }}</p>
                                            <p class="text-xs text-gray-500">NID: {{ $patient->nid }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($patient->pivot->policy_number)
                                            <p class="text-xs font-mono text-gray-700">{{ $patient->pivot->policy_number }}</p>
                                        @endif
                                        @if($patient->pivot->is_primary)
                                            <span class="inline-block px-2 py-0.5 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">Principal</span>
                                        @endif
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
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i> Informações
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID:</span>
                        <span class="font-mono text-gray-900">#{{ $insurance->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Criada em:</span>
                        <span class="font-medium text-gray-900">{{ $insurance->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Última atualização:</span>
                        <span class="font-medium text-gray-900">{{ $insurance->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-bolt text-yellow-500"></i> Ações Rápidas
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('insurances.edit', $insurance->id) }}" 
                       class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">Editar Seguradora</p>
                            <p class="text-xs text-gray-500">Alterar dados</p>
                        </div>
                    </a>
                    
                    <form method="POST" action="{{ route('insurances.toggleStatus', $insurance->id) }}">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('{{ $insurance->is_active ? 'Desativar' : 'Ativar' }} esta seguradora?');"
                                class="w-full flex items-center gap-3 p-3 bg-amber-50 hover:bg-amber-100 rounded-lg transition text-left">
                            <div class="w-10 h-10 bg-amber-600 rounded-lg flex items-center justify-center text-white">
                                <i class="fas fa-{{ $insurance->is_active ? 'ban' : 'check' }}"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $insurance->is_active ? 'Desativar' : 'Ativar' }}</p>
                                <p class="text-xs text-gray-500">Bloquear ou liberar</p>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>