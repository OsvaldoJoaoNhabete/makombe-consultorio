<x-layouts.admin title="Pacientes">

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pacientes</h1>
            <p class="text-gray-600">Gestão completa de pacientes do consultório</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('patients.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg transition">
                <i class="fas fa-plus"></i> Novo Paciente
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
            <p class="text-sm text-green-700 font-medium"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="relative flex-1">
                <input type="text" name="search" placeholder="Buscar paciente..." 
                       value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition"
                       autofocus>
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('patients.index', ['status' => 'all']) }}" 
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg {{ request('status') === 'all' ? 'bg-purple-50 text-purple-600 font-medium' : '' }}">
                    Todos
                </a>
                <a href="{{ route('patients.index', ['status' => 'active']) }}" 
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg {{ request('status') === 'active' ? 'bg-purple-50 text-purple-600 font-medium' : '' }}">
                    Ativos
                </a>
                <a href="{{ route('patients.index', ['status' => 'inactive']) }}" 
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg {{ request('status') === 'inactive' ? 'bg-purple-50 text-purple-600 font-medium' : '' }}">
                    Inativos
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-gray-600">Nome Completo</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">NID / BI</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Idade</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Género</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Telemóvel</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Estado</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 text-sm font-bold">
                                        {{ $patient->getInitial() }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $patient->full_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $patient->email ?? 'Não informado' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $patient->nid }}</p>
                                <p class="text-xs text-gray-500">{{ $patient->bi_number ?? 'Não informado' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($patient->birth_date)
                                    {{ $patient->age }} anos
                                @else
                                    Não informado
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                {{ $patient->gender ? ucfirst($patient->gender) : 'Não informado' }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="tel:{{ $patient->phone }}" class="text-purple-600 hover:text-purple-800 font-medium">
                                    +258 {{ $patient->phone }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($patient->is_active)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Ativo
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <i class="fas fa-ban mr-1"></i> Inativo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('patients.show', $patient->id) }}" class="p-2 text-gray-500 hover:text-purple-600 rounded-lg hover:bg-purple-50 transition">
                                        <i class="fas fa-eye" title="Ver detalhes"></i>
                                    </a>
                                    @canany(['Administrador', 'Gerente'])
                                        <a href="{{ route('patients.edit', $patient->id) }}" class="p-2 text-gray-500 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition">
                                            <i class="fas fa-edit" title="Editar"></i>
                                        </a>
                                        <form action="{{ route('patients.toggle-status', $patient->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Alterar estado">
                                                @if($patient->is_active)
                                                    <i class="fas fa-ban"></i>
                                                @else
                                                    <i class="fas fa-check-circle"></i>
                                                @endif
                                            </button>
                                        </form>
                                    @endcanany
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-users text-4xl mb-3"></i>
                                <p class="text-lg font-medium">Nenhum paciente encontrado</p>
                                <p class="text-gray-500 mt-1">Tente ajustar os filtros ou crie um novo paciente</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="text-gray-500 text-sm mb-3 md:mb-0">
                Mostrando {{ $patients->firstItem() }} a {{ $patients->lastItem() }} de {{ $patients->total() }} resultados
            </div>
            <div class="flex justify-center">
                {{ $patients->links() }}
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total de Pacientes</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Pacientes Ativos</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['ativos'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Pacientes Inativos</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['inativos'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Pacientes Hoje</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['hoje'] }}</p>
        </div>
    </div>
</x-layouts.admin>