<x-layouts.admin title="Pacientes">

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">👥 Pacientes</h1>
            <p class="text-gray-600">Gestão de pacientes do consultório</p>
        </div>
        <a href="{{ route('patients.create') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition">
            <i class="fas fa-user-plus"></i> Novo Paciente
        </a>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('patients.index') }}" class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </a>
        <a href="{{ route('patients.index', ['status' => 'active']) }}" class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-green-600 uppercase font-semibold">Ativos</p>
                    <p class="text-2xl font-bold text-green-700">{{ $stats['ativos'] }}</p>
                </div>
            </div>
        </a>
        <a href="{{ route('patients.index', ['status' => 'inactive']) }}" class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-red-600 uppercase font-semibold">Inativos</p>
                    <p class="text-2xl font-bold text-red-700">{{ $stats['inativos'] }}</p>
                </div>
            </div>
        </a>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-plus text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-purple-600 uppercase font-semibold">Hoje</p>
                    <p class="text-2xl font-bold text-purple-700">{{ $stats['hoje'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('patients.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ $search }}" 
                       placeholder="🔍 Buscar por nome, NID, telefone ou email..."
                       class="w-full px-4 py-2.5 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            <select name="status" class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos os Status</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>✅ Ativos</option>
                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>❌ Inativos</option>
            </select>
            <select name="gender" class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all" {{ $gender === 'all' ? 'selected' : '' }}>Todos os Géneros</option>
                <option value="masculino" {{ $gender === 'masculino' ? 'selected' : '' }}>♂ Masculino</option>
                <option value="feminino" {{ $gender === 'feminino' ? 'selected' : '' }}>♀ Feminino</option>
                <option value="outro" {{ $gender === 'outro' ? 'selected' : '' }}>⚧ Outro</option>
            </select>
            <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                <i class="fas fa-filter mr-1"></i> Filtrar
            </button>
            <a href="{{ route('patients.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                <i class="fas fa-redo mr-1"></i> Limpar
            </a>
        </form>
    </div>

    <!-- Tabela de Pacientes -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($patients->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Paciente</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">NID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Contacto</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Género</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($patients as $patient)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full overflow-hidden bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                                            @if($patient->hasPhoto())
                                                <img src="{{ $patient->getPhotoUrl() }}" alt="{{ $patient->full_name }}" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($patient->full_name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm">{{ $patient->full_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $patient->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded">{{ $patient->nid }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900">
                                        <i class="fas fa-phone text-gray-400 mr-1 text-xs"></i> +258 {{ $patient->phone }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $genderIcon = match($patient->gender) {
                                            'masculino' => '♂ Masculino',
                                            'feminino' => '♀ Feminino',
                                            default => '⚧ Outro',
                                        };
                                    @endphp
                                    <span class="text-sm">{{ $genderIcon }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $patient->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $patient->is_active ? '✅ Ativo' : '❌ Inativo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('patients.show', $patient->id) }}" 
                                           class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg transition text-xs font-medium"
                                           title="Ver detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('patients.edit', $patient->id) }}" 
                                           class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition text-xs font-medium"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('patients.toggleStatus', $patient->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('{{ $patient->is_active ? 'Desativar' : 'Ativar' }} este paciente?');"
                                                    class="px-3 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition text-xs font-medium"
                                                    title="{{ $patient->is_active ? 'Desativar' : 'Ativar' }}">
                                                <i class="fas fa-{{ $patient->is_active ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('patients.destroy', $patient->id) }}" 
                                              onsubmit="return confirm('Tem certeza que deseja excluir este paciente?');"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition text-xs font-medium"
                                                    title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($patients->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $patients->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Nenhum paciente encontrado</h4>
                <p class="text-gray-500 mb-4">
                    @if($search || $status !== 'all' || $gender !== 'all')
                        Não há pacientes para os filtros selecionados.
                    @else
                        Comece por criar o primeiro paciente.
                    @endif
                </p>
                <a href="{{ route('patients.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition">
                    <i class="fas fa-user-plus"></i> Criar Primeiro Paciente
                </a>
            </div>
        @endif
    </div>

</x-layouts.admin>