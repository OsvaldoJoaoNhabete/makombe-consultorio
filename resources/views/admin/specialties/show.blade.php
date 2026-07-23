<x-layouts.admin title="Detalhes da Especialidade">

    <!-- Cabeçalho -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Detalhes da Especialidade</h1>
            <p class="text-gray-600">Informações completas de {{ $specialty->name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.specialties.edit', $specialty->id) }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg transition">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('admin.specialties.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-xl shadow-lg transition">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <!-- Cards de Informação -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <!-- Ícone e Info Básica -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="w-24 h-24 mx-auto rounded-xl flex items-center justify-center text-white text-4xl mb-4 shadow-lg" 
                 style="background-color: {{ $specialty->color ?? '#6d28d9' }}">
                <i class="fas {{ $specialty->icon ?? 'fa-stethoscope' }}"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $specialty->name }}</h2>
            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $specialty->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $specialty->is_active ? '✅ Ativa' : '❌ Inativa' }}
            </span>
        </div>

        <!-- Informações Gerais -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-purple-600"></i> Informações Gerais
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Descrição</p>
                    <p class="text-gray-900 font-medium">{{ $specialty->description ?? 'Sem descrição' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Total de Médicos</p>
                    <p class="text-gray-900 font-medium text-2xl">{{ $specialty->users_count }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Data de Criação</p>
                    <p class="text-gray-900 font-medium">{{ $specialty->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Última Atualização</p>
                    <p class="text-gray-900 font-medium">{{ $specialty->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Médicos Associados -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-user-md text-purple-600"></i> Médicos Associados ({{ $medicos->count() }})
        </h3>
        
        @if($medicos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($medicos as $medico)
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                            @if($medico->photo)
                                <img src="{{ asset('storage/' . $medico->photo) }}" alt="{{ $medico->name }}" class="w-full h-full rounded-full object-cover">
                            @else
                                {{ strtoupper(substr($medico->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $medico->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $medico->email }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-user-slash text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">Nenhum médico associado a esta especialidade.</p>
            </div>
        @endif
    </div>

</x-layouts.admin>