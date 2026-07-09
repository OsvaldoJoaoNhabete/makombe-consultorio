<x-layouts.admin title="Consulta #{{ $consultation->id }}">

    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('doctor.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
        @if(in_array($consultation->status, ['agendada', 'confirmada', 'em_andamento']))
            <a href="{{ route('doctor.attend', $consultation->id) }}" 
               class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white hover:bg-blue-700 rounded-lg text-sm font-medium">
                <i class="fas fa-stethoscope"></i> Atender
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-6 text-white">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-3xl">
                                    {{ $consultation->type === 'presencial' ? '🏥' : ($consultation->type === 'teleconsulta' ? '💻' : '🏠') }}
                                </span>
                                <h1 class="text-2xl font-bold">Consulta {{ ucfirst($consultation->type) }}</h1>
                            </div>
                            <p class="text-blue-100">
                                {{ $consultation->scheduled_at->format('d/m/Y \à\s H:i') }}
                            </p>
                        </div>
                        <div class="text-right">
                            @php
                                $statusClass = match($consultation->status) {
                                    'agendada' => 'bg-blue-500',
                                    'em_andamento' => 'bg-amber-500',
                                    'concluida' => 'bg-green-500',
                                    'cancelada' => 'bg-red-500',
                                    default => 'bg-gray-500',
                                };
                            @endphp
                            <span class="inline-block px-4 py-2 {{ $statusClass }} text-white rounded-full font-semibold">
                                {{ ucfirst(str_replace('_', ' ', $consultation->status)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Paciente</p>
                            <p class="text-gray-900 font-semibold">{{ $consultation->patient->full_name }}</p>
                            <p class="text-sm text-gray-600">NID: {{ $consultation->patient->nid }}</p>
                            <p class="text-sm text-gray-600">📞 +258 {{ $consultation->patient->phone }}</p>
                        </div>
                        @if($consultation->insurance)
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Seguradora</p>
                                <p class="text-gray-900">{{ $consultation->insurance->name }}</p>
                            </div>
                        @endif
                        @if($consultation->total_amount > 0)
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Valor</p>
                                <p class="text-gray-900 font-bold">{{ number_format($consultation->total_amount, 2, ',', '.') }} MT</p>
                            </div>
                        @endif
                    </div>

                    @if($consultation->clinical_notes)
                        <div class="mt-6 pt-6 border-t">
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Queixa Principal</p>
                            <p class="text-gray-700">{{ $consultation->clinical_notes }}</p>
                        </div>
                    @endif

                    @if($consultation->diagnosis)
                        <div class="mt-6 pt-6 border-t">
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-2">
                                <i class="fas fa-stethoscope text-green-600 mr-1"></i>Diagnóstico
                            </p>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-gray-800">
                                {{ $consultation->diagnosis }}
                            </div>
                        </div>
                    @endif

                    @if($consultation->prescription)
                        <div class="mt-6 pt-6 border-t">
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-2">
                                <i class="fas fa-prescription text-purple-600 mr-1"></i>Prescrição
                            </p>
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-gray-800 font-mono text-sm whitespace-pre-line">
                                {{ $consultation->prescription }}
                            </div>
                        </div>
                    @endif

                    @if($consultation->type === 'teleconsulta' && $consultation->location)
                        <div class="mt-6 pt-6 border-t">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-6">
                                <div class="flex items-center justify-between flex-wrap gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-video text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-green-900">Link da Videochamada</p>
                                            <p class="text-xs text-green-700 font-mono break-all">{{ $consultation->location }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ $consultation->location }}" target="_blank" 
                                       class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition flex items-center gap-2 shadow-md">
                                        <i class="fas fa-video"></i>
                                        <span>Iniciar Videochamada</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($patientHistory->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-history text-purple-600"></i> Histórico do Paciente
                        </h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($patientHistory as $history)
                            <div class="p-4 hover:bg-gray-50">
                                <p class="font-semibold text-gray-900 text-sm">
                                    {{ $history->scheduled_at->format('d/m/Y') }}
                                </p>
                                <p class="text-xs text-gray-600 mt-1">
                                    Dr(a). {{ $history->doctor->name ?? '-' }}
                                </p>
                                @if($history->diagnosis)
                                    <p class="text-xs text-gray-500 mt-1">
                                        <strong>DX:</strong> {{ Str::limit($history->diagnosis, 100) }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-blue-600"></i> Paciente
                </h3>
                <div class="flex items-center gap-4 mb-4 pb-4 border-b">
                    <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr($consultation->patient->full_name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">{{ $consultation->patient->full_name }}</p>
                        <p class="text-xs text-gray-500">NID: {{ $consultation->patient->nid }}</p>
                    </div>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email:</span>
                        <span class="font-medium text-gray-900">{{ $consultation->patient->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Telefone:</span>
                        <span class="font-medium text-gray-900">+258 {{ $consultation->patient->phone }}</span>
                    </div>
                    @if($consultation->patient->birth_date)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Nascimento:</span>
                            <span class="font-medium text-gray-900">
                                {{ $consultation->patient->birth_date->format('d/m/Y') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>