<x-layouts.admin title="Especialidades">

    <!-- Cabeçalho -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Especialidades Médicas</h1>
            <p class="text-gray-600">Gestão das especialidades do consultório</p>
        </div>
        <a href="{{ route('admin.specialties.create') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg transition">
            <i class="fas fa-plus"></i> Nova Especialidade
        </a>
    </div>

    <!-- Mensagens de Sucesso/Erro -->
    @if (session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
            <p class="text-sm text-green-700 font-medium">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </p>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
            <p class="text-sm text-red-700 font-medium">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </p>
        </div>
    @endif

    <!-- Estatísticas -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-stethoscope text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-green-600 uppercase font-semibold">Ativas</p>
                    <p class="text-2xl font-bold text-green-700">{{ $stats['ativas'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-red-600 uppercase font-semibold">Inativas</p>
                    <p class="text-2xl font-bold text-red-700">{{ $stats['inativas'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-md text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-purple-600 uppercase font-semibold">Com Médicos</p>
                    <p class="text-2xl font-bold text-purple-700">{{ $stats['com_medicos'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
        <form method="GET" action="{{ route('admin.specialties.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ $search }}" 
                       placeholder="🔍 Buscar por nome ou descrição..."
                       class="w-full px-4 py-2.5 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            <select name="status" class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos os Status</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>✅ Ativas</option>
                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>❌ Inativas</option>
            </select>
            <button type="submit" class="px-4 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium">
                <i class="fas fa-filter mr-1"></i> Filtrar
            </button>
            <a href="{{ route('admin.specialties.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">
                <i class="fas fa-redo mr-1"></i> Limpar
            </a>
        </form>
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        @if($specialties->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Especialidade</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Descrição</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Médicos</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($specialties as $specialty)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold" 
                                             style="background-color: {{ $specialty->color ?? '#6d28d9' }}">
                                            <i class="fas {{ $specialty->icon ?? 'fa-stethoscope' }}"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm">{{ $specialty->name }}</p>
                                            <p class="text-xs text-gray-500">Criada em {{ $specialty->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600">{{ Str::limit($specialty->description, 50) ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $specialty->users_count > 0 ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $specialty->users_count }} médico(s)
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $specialty->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $specialty->is_active ? '✅ Ativa' : '❌ Inativa' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.specialties.show', $specialty->id) }}" 
                                           class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-xs"
                                           title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.specialties.edit', $specialty->id) }}" 
                                           class="px-3 py-1.5 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg text-xs"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.specialties.toggle', $specialty->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('{{ $specialty->is_active ? 'Desativar' : 'Ativar' }} esta especialidade?');"
                                                    class="px-3 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg text-xs"
                                                    title="{{ $specialty->is_active ? 'Desativar' : 'Ativar' }}">
                                                <i class="fas fa-{{ $specialty->is_active ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.specialties.destroy', $specialty->id) }}" 
                                              onsubmit="return confirm('Tem certeza que deseja eliminar esta especialidade?');"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs"
                                                    title="Eliminar">
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

            @if($specialties->hasPages())
                <div class="px-6 py-4 border-t bg-gray-50">
                    {{ $specialties->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <i class="fas fa-stethoscope text-6xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Nenhuma especialidade encontrada</h4>
                <p class="text-gray-500 mb-4">
                    @if($search || $status !== 'all')
                        Não há especialidades para os filtros selecionados.
                    @else
                        Comece por criar a primeira especialidade médica.
                    @endif
                </p>
                <a href="{{ route('admin.specialties.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl transition">
                    <i class="fas fa-plus"></i> Criar Primeira Especialidade
                </a>
            </div>
        @endif
    </div>

</x-layouts.admin>