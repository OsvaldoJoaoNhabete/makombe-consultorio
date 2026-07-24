<x-layouts.admin title="Consulta #{{ $consultation->id }}">

    <!-- Ações -->
    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('consultations.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
        <span class="text-gray-300">|</span>
        <a href="{{ route('consultations.edit', $consultation->id) }}" 
           class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium">
            <i class="fas fa-edit"></i> Editar
        </a>
        @if(in_array($consultation->status, ['agendada', 'confirmada', 'em_andamento']))
            <form method="POST" action="{{ route('consultations.complete', $consultation->id) }}" class="inline">
                @csrf
                <button type="submit" onclick="return confirm('Marcar como concluída?');"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 text-green-600 hover:bg-green-100 rounded-lg text-sm font-medium">
                    <i class="fas fa-check"></i> Concluir
                </button>
            </form>
            <a href="{{ route('consultations.print.note', $consultation->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition">
    <i class="fas fa-print"></i> Imprimir Nota Médica
</a>
            <form method="POST" action="{{ route('consultations.cancel', $consultation->id) }}" class="inline">
                @csrf
                <button type="submit" onclick="return confirm('Cancelar consulta?');"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium">
                    <i class="fas fa-times"></i> Cancelar
                </button>
            </form>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Coluna Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card da Consulta -->
            @php
                $statusClass = match($consultation->status) {
                    'agendada' => 'from-blue-600 to-blue-700',
                    'confirmada' => 'from-indigo-600 to-indigo-700',
                    'em_andamento' => 'from-amber-600 to-amber-700',
                    'concluida' => 'from-green-600 to-green-700',
                    'cancelada' => 'from-red-600 to-red-700',
                    'faltou' => 'from-gray-600 to-gray-700',
                    default => 'from-gray-600 to-gray-700',
                };
            @endphp
            
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="bg-gradient-to-r {{ $statusClass }} px-6 py-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-80">Consulta #{{ $consultation->id }}</p>
                            <h1 class="text-3xl font-bold mt-1">{{ $consultation->scheduled_at->format('d/m/Y \à\s H:i') }}</h1>
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
                    <!-- Paciente e Médico -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Paciente</p>
                            <a href="{{ route('patients.show', $consultation->patient_id) }}" class="block hover:text-blue-600">
                                <p class="font-bold text-gray-900 text-lg">{{ $consultation->patient->full_name }}</p>
                                <p class="text-sm text-gray-600">NID: {{ $consultation->patient->nid }}</p>
                                <p class="text-sm text-gray-600">📞 +258 {{ $consultation->patient->phone }}</p>
                            </a>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Médico</p>
                            <p class="font-bold text-gray-900 text-lg">{{ $consultation->doctor->name }}</p>
                            <p class="text-sm text-gray-600">📧 {{ $consultation->doctor->email }}</p>
                        </div>
                    </div>

                    @if($consultation->location)
                        <!-- Link de Videochamada -->
                        <div class="p-4 bg-purple-50 border border-purple-200 rounded-xl">
                            <p class="text-xs text-purple-800 uppercase font-semibold mb-2">
                                <i class="fas fa-video mr-1"></i> Link da Videochamada
                            </p>
                            <a href="{{ $consultation->location }}" target="_blank" 
                               class="text-purple-700 font-mono text-sm break-all hover:underline">
                                {{ $consultation->location }}
                            </a>
                            <button onclick="copyLink()" 
                                    class="mt-2 px-3 py-1.5 bg-purple-600 text-white text-xs rounded-lg hover:bg-purple-700">
                                <i class="fas fa-copy mr-1"></i> Copiar Link
                            </button>
                        </div>
                    @endif

                    <!-- Detalhes Financeiros -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-xl">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Valor Total</p>
                            <p class="font-bold text-gray-900 mt-1">{{ number_format($consultation->total_amount, 2, ',', '.') }} MT</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Seguradora</p>
                            <p class="font-medium text-gray-900 mt-1 text-sm">{{ $consultation->insurance->name ?? 'Particular' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Cobertura</p>
                            <p class="font-medium text-gray-900 mt-1">{{ number_format($consultation->insurance_coverage, 2, ',', '.') }} MT</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Paciente Paga</p>
                            <p class="font-bold text-green-600 mt-1">{{ number_format($consultation->patient_amount, 2, ',', '.') }} MT</p>
                        </div>
                    </div>

                    @if($consultation->notes)
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Observações</p>
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-gray-800">
                                {{ $consultation->notes }}
                            </div>
                        </div>
                    @endif

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

                    @if($consultation->prescription)
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-2">
                                <i class="fas fa-prescription text-purple-600 mr-1"></i> Prescrição
                            </p>
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-sm text-gray-800 whitespace-pre-line">
                                {{ $consultation->prescription }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Coluna Lateral -->
        <div class="space-y-6">
            
            <!-- Histórico do Paciente -->
            @if($patientHistory->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-history text-blue-600"></i> Histórico do Paciente
                    </h3>
                    <div class="space-y-3">
                        @foreach($patientHistory as $h)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm font-semibold text-gray-900">{{ $h->scheduled_at->format('d/m/Y') }}</p>
                                <p class="text-xs text-gray-600">Dr(a). {{ $h->doctor->name ?? '-' }}</p>
                                @if($h->diagnosis)
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">DX: {{ $h->diagnosis }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Informações do Sistema -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i> Informações
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID:</span>
                        <span class="font-mono">#{{ $consultation->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Criada:</span>
                        <span class="font-medium">{{ $consultation->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Atualizada:</span>
                        <span class="font-medium">{{ $consultation->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($consultation->createdBy)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Criada por:</span>
                            <span class="font-medium">{{ $consultation->createdBy->name }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyLink() {
            navigator.clipboard.writeText('{{ $consultation->location }}');
            alert('Link copiado!');
        }
    </script>

</x-layouts.admin>