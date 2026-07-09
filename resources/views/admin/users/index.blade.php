<x-layouts.admin title="Utilizadores">

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2"> Utilizadores (Staff)</h1>
            <p class="text-gray-600">Gestão da equipa do consultório</p>
        </div>
        <a href="{{ route('users.create') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition">
            <i class="fas fa-user-plus"></i> Novo Utilizador
        </a>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('users.index') }}" class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
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
        <a href="{{ route('users.index', ['status' => 'active']) }}" class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
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
        <a href="{{ route('users.index', ['status' => 'inactive']) }}" class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
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
        <a href="{{ route('users.index', ['role' => 'Medico']) }}" class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-md text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-purple-600 uppercase font-semibold">Médicos</p>
                    <p class="text-2xl font-bold text-purple-700">{{ $stats['medicos'] }}</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ $search }}" 
                       placeholder="🔍 Buscar por nome, email ou telefone..."
                       class="w-full px-4 py-2.5 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            <select name="role" class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all" {{ $role === 'all' ? 'selected' : '' }}>Todas as Funções</option>
                @foreach($roles as $r)
                    <option value="{{ $r->name }}" {{ $role === $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                @endforeach
            </select>
            <select name="status" class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos os Status</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>✅ Ativos</option>
                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>❌ Inativos</option>
            </select>
            <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                <i class="fas fa-filter mr-1"></i> Filtrar
            </button>
            <a href="{{ route('users.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">
                <i class="fas fa-redo mr-1"></i> Limpar
            </a>
        </form>
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Utilizador</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Contacto</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Função</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Criado</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full overflow-hidden bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                                            @if($user->hasPhoto())
                                                <img src="{{ $user->getPhotoUrl() }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->phone)
                                        <p class="text-sm text-gray-900">
                                            <i class="fas fa-phone text-gray-400 mr-1 text-xs"></i> +258 {{ $user->phone }}
                                        </p>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $roleColors = [
                                            'Administrador' => 'bg-red-100 text-red-800',
                                            'Gerente' => 'bg-purple-100 text-purple-800',
                                            'Medico' => 'bg-blue-100 text-blue-800',
                                            'Enfermeiro' => 'bg-green-100 text-green-800',
                                            'Atendente' => 'bg-amber-100 text-amber-800',
                                            'Financeiro' => 'bg-indigo-100 text-indigo-800',
                                        ];
                                    @endphp
                                    @foreach($user->roles as $r)
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $roleColors[$r->name] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $r->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? '✅ Ativo' : '❌ Inativo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('users.show', $user->id) }}" 
                                           class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-xs"
                                           title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user->id) }}" 
                                           class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->id !== Auth::id())
                                            <form method="POST" action="{{ route('users.toggleStatus', $user->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('{{ $user->is_active ? 'Desativar' : 'Ativar' }} este utilizador?');"
                                                        class="px-3 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg text-xs"
                                                        title="{{ $user->is_active ? 'Desativar' : 'Ativar' }}">
                                                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('users.destroy', $user->id) }}" 
                                                  onsubmit="return confirm('Tem certeza que deseja excluir este utilizador?');"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs"
                                                        title="Excluir">
                                                    <i class="fas fa-trash"></i>
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

            @if($users->hasPages())
                <div class="px-6 py-4 border-t bg-gray-50">
                    {{ $users->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Nenhum utilizador encontrado</h4>
                <p class="text-gray-500 mb-4">
                    @if($search || $role !== 'all' || $status !== 'all')
                        Não há utilizadores para os filtros selecionados.
                    @else
                        Comece por criar o primeiro membro da equipa.
                    @endif
                </p>
                <a href="{{ route('users.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition">
                    <i class="fas fa-user-plus"></i> Criar Primeiro Utilizador
                </a>
            </div>
        @endif
    </div>

</x-layouts.admin>