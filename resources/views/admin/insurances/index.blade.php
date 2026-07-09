<x-layouts.admin title="Seguradoras">

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">🛡️ Seguradoras</h1>
            <p class="text-gray-600">Gestão das seguradoras parceiras do consultório</p>
        </div>
        <a href="{{ route('insurances.create') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition">
            <i class="fas fa-plus"></i> Nova Seguradora
        </a>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('insurances.index') }}" class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shield-alt text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </a>
        <a href="{{ route('insurances.index', ['status' => 'active']) }}" class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-green-600 uppercase font-semibold">Ativas</p>
                    <p class="text-2xl font-bold text-green-700">{{ $stats['ativas'] }}</p>
                </div>
            </div>
        </a>
        <a href="{{ route('insurances.index', ['status' => 'inactive']) }}" class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-red-600 uppercase font-semibold">Inativas</p>
                    <p class="text-2xl font-bold text-red-700">{{ $stats['inativas'] }}</p>
                </div>
            </div>
        </a>
        <div class="bg-white p-5 rounded-xl shadow-sm border">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-percentage text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-purple-600 uppercase font-semibold">Cobertura Média</p>
                    <p class="text-2xl font-bold text-purple-700">{{ number_format($stats['cobertura_media'], 0) }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
        <form method="GET" action="{{ route('insurances.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ $search }}" 
                       placeholder="🔍 Buscar por nome, código ou email..."
                       class="w-full px-4 py-2.5 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            <select name="status" class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos os Status</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>✅ Ativas</option>
                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>❌ Inativas</option>
            </select>
            <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                <i class="fas fa-filter mr-1"></i> Filtrar
            </button>
            <a href="{{ route('insurances.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">
                <i class="fas fa-redo mr-1"></i> Limpar
            </a>
        </form>
    </div>

    <!-- Grid de Seguradoras -->
    @if($insurances->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($insurances as $insurance)
                <div class="bg-white rounded-xl shadow-sm border hover:shadow-lg transition overflow-hidden">
                    <!-- Header com Logo -->
                    <div class="p-6 border-b bg-gradient-to-br from-gray-50 to-gray-100">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-white rounded-lg shadow flex items-center justify-center overflow-hidden flex-shrink-0">
                                @if($insurance->logo_path)
                                    <img src="{{ $insurance->getLogoUrl() }}" alt="{{ $insurance->name }}" class="w-full h-full object-contain p-1">
                                @else
                                    <i class="fas fa-shield-alt text-3xl text-blue-600"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-gray-900 truncate">{{ $insurance->name }}</h3>
                                @if($insurance->code)
                                    <p class="text-xs text-gray-500 font-mono">Código: {{ $insurance->code }}</p>
                                @endif
                                <span class="inline-block mt-1 px-2 py-0.5 text-xs font-semibold rounded-full {{ $insurance->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $insurance->is_active ? '✅ Ativa' : '❌ Inativa' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Informações -->
                    <div class="p-4 space-y-2 text-sm">
                        @if($insurance->coverage_percentage > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600"><i class="fas fa-percentage text-purple-500 mr-1"></i> Cobertura</span>
                                <span class="font-bold text-purple-700">{{ number_format($insurance->coverage_percentage, 0) }}%</span>
                            </div>
                        @endif
                        @if($insurance->phone)
                            <div class="flex items-center gap-2 text-gray-700">
                                <i class="fas fa-phone text-blue-500 w-4"></i>
                                <span class="truncate">{{ $insurance->phone }}</span>
                            </div>
                        @endif
                        @if($insurance->email)
                            <div class="flex items-center gap-2 text-gray-700">
                                <i class="fas fa-envelope text-blue-500 w-4"></i>
                                <span class="truncate">{{ $insurance->email }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Ações -->
                    <div class="p-4 border-t bg-gray-50 flex items-center justify-between gap-2">
                        <a href="{{ route('insurances.show', $insurance->id) }}" 
                           class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-xs font-medium">
                            <i class="fas fa-eye mr-1"></i> Ver
                        </a>
                        <a href="{{ route('insurances.edit', $insurance->id) }}" 
                           class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-medium">
                            <i class="fas fa-edit mr-1"></i> Editar
                        </a>
                        <form method="POST" action="{{ route('insurances.toggleStatus', $insurance->id) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('{{ $insurance->is_active ? 'Desativar' : 'Ativar' }} esta seguradora?');"
                                    class="px-3 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg text-xs font-medium">
                                <i class="fas fa-{{ $insurance->is_active ? 'ban' : 'check' }} mr-1"></i>
                                {{ $insurance->is_active ? 'Desativar' : 'Ativar' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if($insurances->hasPages())
            <div class="mt-6 bg-white rounded-xl shadow-sm border p-4">
                {{ $insurances->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
            <i class="fas fa-shield-alt text-6xl text-gray-300 mb-4"></i>
            <h4 class="text-lg font-semibold text-gray-700 mb-2">Nenhuma seguradora encontrada</h4>
            <p class="text-gray-500 mb-4">
                @if($search || $status !== 'all')
                    Não há seguradoras para os filtros selecionados.
                @else
                    Comece por cadastrar a primeira seguradora parceira.
                @endif
            </p>
            <a href="{{ route('insurances.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition">
                <i class="fas fa-plus"></i> Cadastrar Primeira Seguradora
            </a>
        </div>
    @endif

</x-layouts.admin>