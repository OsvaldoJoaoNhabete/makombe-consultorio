<x-layouts.admin title="Consultas">

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">📅 Consultas</h1>
            <p class="text-gray-600">Gestão de consultas médicas</p>
        </div>
        <a href="{{ route('consultations.create') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition">
            <i class="fas fa-plus"></i> Nova Consulta
        </a>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <a href="{{ route('consultations.index') }}" class="bg-white p-4 rounded-xl shadow-sm border hover:shadow-md transition">
            <p class="text-xs text-gray-500 uppercase font-semibold">Total</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </a>
        <a href="{{ route('consultations.index', ['date' => today()->format('Y-m-d')]) }}" class="bg-white p-4 rounded-xl shadow-sm border hover:shadow-md transition">
            <p class="text-xs text-blue-600 uppercase font-semibold">Hoje</p>
            <p class="text-2xl font-bold text-blue-700 mt-1">{{ $stats['hoje'] }}</p>
        </a>
        <a href="{{ route('consultations.index', ['status' => 'agendada']) }}" class="bg-white p-4 rounded-xl shadow-sm border hover:shadow-md transition">
            <p class="text-xs text-amber-600 uppercase font-semibold">Agendadas</p>
            <p class="text-2xl font-bold text-amber-700 mt-1">{{ $stats['agendadas'] }}</p>
        </a>
        <a href="{{ route('consultations.index', ['status' => 'concluida']) }}" class="bg-white p-4 rounded-xl shadow-sm border hover:shadow-md transition">
            <p class="text-xs text-green-600 uppercase font-semibold">Concluídas</p>
            <p class="text-2xl font-bold text-green-700 mt-1">{{ $stats['concluidas'] }}</p>
        </a>
        <a href="{{ route('consultations.index', ['status' => 'cancelada']) }}" class="bg-white p-4 rounded-xl shadow-sm border hover:shadow-md transition">
            <p class="text-xs text-red-600 uppercase font-semibold">Canceladas</p>
            <p class="text-2xl font-bold text-red-700 mt-1">{{ $stats['canceladas'] }}</p>
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
        <form method="GET" action="{{ route('consultations.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <input type="date" name="date" value="{{ $date }}" 
                   class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="status" class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos os Status</option>
                <option value="agendada" {{ $status === 'agendada' ? 'selected' : '' }}>Agendada</option>
                <option value="confirmada" {{ $status === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                <option value="em_andamento" {{ $status === 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                <option value="concluida" {{ $status === 'concluida' ? 'selected' : '' }}>Concluída</option>
                <option value="cancelada" {{ $status === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                <option value="faltou" {{ $status === 'faltou' ? 'selected' : '' }}>Faltou</option>
            </select>
            <select name="type" class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Todos os Tipos</option>
                <option value="presencial" {{ $type === 'presencial' ? 'selected' : '' }}>🏥 Presencial</option>
                <option value="teleconsulta" {{ $type === 'teleconsulta' ? 'selected' : '' }}>💻 Teleconsulta</option>
                <option value="domicilio" {{ $type === 'domicilio' ? 'selected' : '' }}>🏠 Domicílio</option>
            </select>
            <select name="doctor_id" class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all" {{ $doctorId === 'all' ? 'selected' : '' }}>Todos os Médicos</option>
                @foreach($doctors as $doc)
                    <option value="{{ $doc->id }}" {{ $doctorId == $doc->id ? 'selected' : '' }}>{{ $doc->name }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    <i class="fas fa-filter mr-1"></i> Filtrar
                </button>
                <a href="{{ route('consultations.index') }}" class="px-3 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        @if($consultations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data/Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Paciente</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Médico</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Valor</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($consultations as $c)
                            @php
                                $statusClass = match($c->status) {
                                    'agendada' => 'bg-blue-100 text-blue-800',
                                    'confirmada' => 'bg-indigo-100 text-indigo-800',
                                    'em_andamento' => 'bg-amber-100 text-amber-800',
                                    'concluida' => 'bg-green-100 text-green-800',
                                    'cancelada' => 'bg-red-100 text-red-800',
                                    'faltou' => 'bg-gray-100 text-gray-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">{{ $c->scheduled_at->format('d/m/Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $c->scheduled_at->format('H:i') }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('patients.show', $c->patient_id) }}" class="hover:text-blue-600">
                                        <p class="font-medium text-sm">{{ $c->patient->full_name ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $c->patient->nid ?? '-' }}</p>
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm">{{ $c->doctor->name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-sm">
                                        @if($c->type === 'presencial') 🏥
                                        @elseif($c->type === 'teleconsulta') 💻
                                        @else 🏠
                                        @endif
                                        {{ ucfirst($c->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $c->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    {{ number_format($c->total_amount, 2, ',', '.') }} MT
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('consultations.show', $c->id) }}" 
                                           class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-xs"
                                           title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('consultations.edit', $c->id) }}" 
                                           class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(in_array($c->status, ['agendada', 'confirmada', 'em_andamento']))
                                            <form method="POST" action="{{ route('consultations.complete', $c->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Marcar como concluída?');"
                                                        class="px-3 py-1.5 bg-green-50 text-green-600 hover:bg-green-100 rounded-lg text-xs"
                                                        title="Concluir">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('consultations.cancel', $c->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Cancelar esta consulta?');"
                                                        class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs"
                                                        title="Cancelar">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($consultations->hasPages())
                <div class="px-6 py-4 border-t bg-gray-50">
                    {{ $consultations->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <i class="fas fa-calendar text-6xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Nenhuma consulta encontrada</h4>
                <p class="text-gray-500 mb-4">
                    @if($date || $status !== 'all' || $type !== 'all' || $doctorId !== 'all')
                        Não há consultas para os filtros selecionados.
                    @else
                        Comece por agendar a primeira consulta.
                    @endif
                </p>
                <a href="{{ route('consultations.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition">
                    <i class="fas fa-plus"></i> Agendar Consulta
                </a>
            </div>
        @endif
    </div>

</x-layouts.admin>