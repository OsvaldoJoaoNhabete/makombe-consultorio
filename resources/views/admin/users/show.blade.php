<x-layouts.admin title="Detalhes do Utilizador">

    <!-- Cabeçalho -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Detalhes do Utilizador</h1>
            <p class="text-gray-600">Informações completas de {{ $user->name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('users.edit', $user->id) }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg transition">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('users.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-xl shadow-lg transition">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <!-- Cards de Informação -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <!-- Foto e Info Básica -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="text-center">
                <div class="w-32 h-32 mx-auto rounded-full overflow-hidden bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white text-4xl font-bold mb-4">
                    @if($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                
                <div class="mt-4 flex justify-center gap-2">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                    @if($user->must_change_password)
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                            <i class="fas fa-exclamation-triangle mr-1"></i>1ª Entrada
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informações Pessoais -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-user text-purple-600"></i> Informações Pessoais
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Nome Completo</p>
                    <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Endereço de E-mail</p>
                    <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Telefone / Telemóvel</p>
                    <p class="text-gray-900 font-medium">{{ $user->phone ?? 'Não informado' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Especialidade</p>
                    <p class="text-gray-900 font-medium">{{ $user->specialty->name ?? 'Não definida' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Data de Criação</p>
                    <p class="text-gray-900 font-medium">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Última Atualização</p>
                    <p class="text-gray-900 font-medium">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Funções e Permissões -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-shield-alt text-purple-600"></i> Funções e Permissões
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Função Principal</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($user->roles as $role)
                        <span class="px-3 py-1.5 text-sm font-semibold rounded-lg bg-purple-100 text-purple-800">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </div>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Permissões Diretas</p>
                <div class="flex flex-wrap gap-2">
                    @forelse($user->getAllPermissions() as $permission)
                        <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">
                            {{ $permission->name }}
                        </span>
                    @empty
                        <span class="text-sm text-gray-500">Nenhuma permissão direta</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Atividades Recentes -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-history text-purple-600"></i> Atividades Recentes
        </h3>
        <div class="space-y-3">
            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                <i class="fas fa-calendar-plus text-green-500 mt-1"></i>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Conta criada</p>
                    <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @if($user->last_login_at)
            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                <i class="fas fa-sign-in-alt text-blue-500 mt-1"></i>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Último acesso</p>
                    <p class="text-xs text-gray-500">{{ $user->last_login_at->diffForHumans() }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

</x-layouts.admin>