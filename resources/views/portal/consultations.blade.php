<x-layouts.patient title="Minhas Consultas">
    <!-- Nota: Assumindo que você tem um layout base para o paciente chamado layouts.patient ou pode usar layouts.admin adaptado -->

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Minhas Consultas</h1>
            <p class="text-gray-600">Histórico e acompanhamento dos seus atendimentos</p>
        </div>
        <a href="{{ route('patient.schedule') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg transition">
            <i class="fas fa-plus"></i> Marcar Nova Consulta
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
            <p class="text-sm text-green-700 font-medium"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
            <p class="text-sm text-red-700 font-medium"><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($consultations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data/Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Médico</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Especialidade</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($consultations as $c)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($c->scheduled_at)->format('d/m/Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($c->scheduled_at)->format('H:i') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900">Dr(a). {{ $c->doctor->name ?? 'Não atribuído' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-700">{{ $c->doctor->specialty->name ?? 'Clínica Geral' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'agendada' => 'bg-blue-100 text-blue-800',
                                            'confirmada' => 'bg-green-100 text-green-800',
                                            'em_andamento' => 'bg-amber-100 text-amber-800',
                                            'concluida' => 'bg-purple-100 text-purple-800',
                                            'cancelada' => 'bg-red-100 text-red-800',
                                            'faltou' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $color = $statusColors[$c->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                        {{ ucfirst(str_replace('_', ' ', $c->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('patient.consultations.show', $c->id) }}" class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-xs" title="Ver Detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($c->status === 'concluida' && !$c->rating)
                                            <a href="{{ route('patient.consultations.rate', $c->id) }}" class="px-3 py-1.5 bg-yellow-50 text-yellow-700 hover:bg-yellow-100 rounded-lg text-xs font-medium" title="Avaliar Atendimento">
                                                <i class="fas fa-star"></i> Avaliar
                                            </a>
                                        @elseif($c->rating)
                                            <span class="px-3 py-1.5 bg-green-50 text-green-700 rounded-lg text-xs font-medium">
                                                <i class="fas fa-check"></i> Avaliado ({{ $c->rating->rating }}/5)
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($consultations->hasPages())
                <div class="px-6 py-4 border-t bg-gray-50">{{ $consultations->links() }}</div>
            @endif
        @else
            <div class="p-12 text-center">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Nenhuma consulta encontrada</h4>
                <p class="text-gray-500 mb-4">Você ainda não marcou nenhuma consulta no Makombe.</p>
                <a href="{{ route('patient.schedule') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl transition">
                    <i class="fas fa-plus"></i> Marcar Primeira Consulta
                </a>
            </div>
        @endif
    </div>
</x-layouts.patient>