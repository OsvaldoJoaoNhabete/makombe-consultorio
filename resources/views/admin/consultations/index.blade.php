<x-layouts.admin title="Agenda de Consultas">

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Agenda de Consultas</h1>
            <p class="text-gray-600">Gestão e acompanhamento das consultas médicas</p>
        </div>
        <a href="{{ route('consultations.create') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg transition">
            <i class="fas fa-plus"></i> Nova Consulta
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

    <!-- Estatísticas -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center"><i class="fas fa-calendar-day text-blue-600 text-xl"></i></div>
                <div><p class="text-xs text-gray-500 uppercase font-semibold">Hoje</p><p class="text-2xl font-bold text-gray-900">{{ $stats['hoje'] }}</p></div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center"><i class="fas fa-clock text-amber-600 text-xl"></i></div>
                <div><p class="text-xs text-amber-600 uppercase font-semibold">Agendadas</p><p class="text-2xl font-bold text-amber-700">{{ $stats['agendadas'] }}</p></div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center"><i class="fas fa-check-double text-green-600 text-xl"></i></div>
                <div><p class="text-xs text-green-600 uppercase font-semibold">Concluídas</p><p class="text-2xl font-bold text-green-700">{{ $stats['concluidas'] }}</p></div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center"><i class="fas fa-times-circle text-red-600 text-xl"></i></div>
                <div><p class="text-xs text-red-600 uppercase font-semibold">Canceladas</p><p class="text-2xl font-bold text-red-700">{{ $stats['canceladas'] }}</p></div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
        <form method="GET" action="{{ route('consultations.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div class="relative">
                <input type="date" name="date" value="{{ $date ?? today()->format('Y-m-d') }}" 
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <select name="doctor_id" class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                <option value="all">Todos os Médicos</option>
                @foreach($doctors as $doc)
                    <option value="{{ $doc->id }}" {{ $doctorId == $doc->id ? 'selected' : '' }}>{{ $doc->name }}</option>
                @endforeach
            </select>
            <select name="status" class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                <option value="all">Todos os Status</option>
                <option value="agendada" {{ $status === 'agendada' ? 'selected' : '' }}>Agendada</option>
                <option value="confirmada" {{ $status === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                <option value="em_andamento" {{ $status === 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                <option value="concluida" {{ $status === 'concluida' ? 'selected' : '' }}>Concluída</option>
                <option value="cancelada" {{ $status === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                <option value="faltou" {{ $status === 'faltou' ? 'selected' : '' }}>Faltou</option>
            </select>
            <select name="type" class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                <option value="all">Todas as Modalidades</option>
                <option value="presencial" {{ $type === 'presencial' ? 'selected' : '' }}>Presencial</option>
                <option value="teleconsulta" {{ $type === 'teleconsulta' ? 'selected' : '' }}>Teleconsulta</option>
                <option value="domicilio" {{ $type === 'domicilio' ? 'selected' : '' }}>Domicílio</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium"><i class="fas fa-filter mr-1"></i> Filtrar</button>
                <a href="{{ route('consultations.index') }}" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium text-center"><i class="fas fa-redo mr-1"></i></a>
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
                                    <p class="text-sm font-medium text-gray-900">{{ $c->patient->full_name }}</p>
                                    <p class="text-xs text-gray-500">NID: {{ $c->patient->nid }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900">Dr(a). {{ $c->doctor->name }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if($c->type === 'teleconsulta')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            <i class="fas fa-video"></i> Teleconsulta
                                        </span>
                                    @elseif($c->type === 'domicilio')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                            <i class="fas fa-home"></i> Domicílio
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            <i class="fas fa-hospital"></i> Presencial
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php $badge = $c->status_badge; @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full {{ $badge['color'] }}">
                                        <i class="fas {{ $badge['icon'] }}"></i> {{ ucfirst(str_replace('_', ' ', $c->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('consultations.show', $c->id) }}" class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-xs" title="Ver Detalhes"><i class="fas fa-eye"></i></a>
                                        
                                        @if(!in_array($c->status, ['concluida', 'cancelada', 'faltou']))
                                            <a href="{{ route('consultations.edit', $c->id) }}" class="px-3 py-1.5 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg text-xs" title="Editar"><i class="fas fa-edit"></i></a>
                                            
                                            @if($c->status !== 'em_andamento')
                                                <form method="POST" action="{{ route('consultations.complete', $c->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Marcar esta consulta como concluída?');" class="px-3 py-1.5 bg-green-50 text-green-600 hover:bg-green-100 rounded-lg text-xs" title="Concluir"><i class="fas fa-check"></i></button>
                                                </form>
                                            @endif
                                            
                                            <form method="POST" action="{{ route('consultations.cancel', $c->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Tem certeza que deseja cancelar esta consulta?');" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs" title="Cancelar"><i class="fas fa-times"></i></button>
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
                <div class="px-6 py-4 border-t bg-gray-50">{{ $consultations->links() }}</div>
            @endif
        @else
            <div class="p-12 text-center">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Nenhuma consulta encontrada</h4>
                <p class="text-gray-500 mb-4">Tente ajustar os filtros ou agende uma nova consulta.</p>
                <a href="{{ route('consultations.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl transition">
                    <i class="fas fa-plus"></i> Agendar Consulta
                </a>
            </div>
        @endif
    </div>

</x-layouts.admin>