<x-layouts.admin title="Meu Atendimento">

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">🩺 Meu Atendimento</h1>
            <p class="text-gray-600">Painel do médico - Gerencie suas consultas</p>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-5 rounded-xl shadow-sm border">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-amber-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-amber-600 uppercase font-semibold">Hoje</p>
                    <p class="text-2xl font-bold text-amber-700">{{ $stats['hoje'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-indigo-600 uppercase font-semibold">Agendadas</p>
                    <p class="text-2xl font-bold text-indigo-700">{{ $stats['agendadas'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-green-600 uppercase font-semibold">Concluídas</p>
                    <p class="text-2xl font-bold text-green-700">{{ $stats['concluidas'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
        <form method="GET" action="{{ route('doctor.index') }}" class="flex flex-col md:flex-row gap-3">
            <input type="date" name="date" value="{{ $filterDate }}" 
                   class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="status" class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all" {{ $filterStatus === 'all' ? 'selected' : '' }}>Todos os Status</option>
                <option value="agendada" {{ $filterStatus === 'agendada' ? 'selected' : '' }}>Agendada</option>
                <option value="confirmada" {{ $filterStatus === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                <option value="em_andamento" {{ $filterStatus === 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                <option value="concluida" {{ $filterStatus === 'concluida' ? 'selected' : '' }}>Concluída</option>
                <option value="cancelada" {{ $filterStatus === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
            <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                <i class="fas fa-filter mr-1"></i> Filtrar
            </button>
            <a href="{{ route('doctor.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                <i class="fas fa-redo"></i>
            </a>
        </form>
    </div>

    <!-- Lista de Consultas -->
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
                    <div class="p-4 hover:bg-gray-50 transition">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 rounded-xl flex items-center justify-center text-3xl
                                    {{ $consultation->type === 'teleconsulta' ? 'bg-purple-100' : ($consultation->type === 'domicilio' ? 'bg-amber-100' : 'bg-blue-100') }}">
                                    {{ $consultation->type === 'presencial' ? '🏥' : ($consultation->type === 'teleconsulta' ? '💻' : '🏠') }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between flex-wrap gap-2 mb-2">
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-lg">
                                            {{ $consultation->scheduled_at->format('d/m/Y \à\s H:i') }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-user text-blue-600 mr-1"></i>
                                            <strong>{{ $consultation->patient->full_name ?? '-' }}</strong>
                                            <span class="text-xs text-gray-500 ml-2">NID: {{ $consultation->patient->nid ?? '-' }}</span>
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-phone text-gray-400 mr-1"></i> +258 {{ $consultation->patient->phone ?? '-' }}
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $consultation->status)) }}
                                    </span>
                                </div>

                                @if($consultation->clinical_notes)
                                    <div class="mt-2 p-2 bg-gray-50 border border-gray-200 rounded-lg">
                                        <p class="text-xs text-gray-600"><strong>Queixa:</strong> {{ $consultation->clinical_notes }}</p>
                                    </div>
                                @endif

                                <!-- Botões de Ação -->
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @if(in_array($consultation->status, ['agendada', 'confirmada', 'em_andamento']))
                                        <a href="{{ route('doctor.attend', $consultation->id) }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
                                            <i class="fas fa-stethoscope"></i>
                                            <span>Atender</span>
                                        </a>
                                    @endif

                                    <a href="{{ route('doctor.show', $consultation->id) }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
                                        <i class="fas fa-eye"></i>
                                        <span>Ver Detalhes</span>
                                    </a>

                                    @if($consultation->type === 'teleconsulta' && $consultation->location)
                                        <a href="{{ $consultation->location }}" target="_blank"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                                            <i class="fas fa-video"></i>
                                            <span>Videochamada</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center">
                <i class="fas fa-calendar-check text-6xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Nenhuma consulta encontrada</h4>
                <p class="text-gray-500">Não há consultas para os filtros selecionados.</p>
            </div>
        @endif
    </div>

</x-layouts.admin>