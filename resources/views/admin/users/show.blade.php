<x-layouts.admin title="Utilizador: {{ $user->name }}">

    <!-- Ações -->
    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
        <span class="text-gray-300">|</span>
        <a href="{{ route('users.edit', $user->id) }}" 
           class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium">
            <i class="fas fa-edit"></i> Editar
        </a>
        @if($user->id !== Auth::id())
            <form method="POST" action="{{ route('users.toggleStatus', $user->id) }}" class="inline">
                @csrf
                <button type="submit" 
                        onclick="return confirm('{{ $user->is_active ? 'Desativar' : 'Ativar' }} este utilizador?');"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg text-sm font-medium">
                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                    {{ $user->is_active ? 'Desativar' : 'Ativar' }}
                </button>
            </form>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Coluna Principal -->
        <div class="lg:col-span-2">
            
            <!-- Card de Perfil -->
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
                    <div class="flex items-center gap-6 relative z-10">
                        <div class="h-24 w-24 rounded-full bg-white flex items-center justify-center shadow-xl overflow-hidden">
                            @if($user->hasPhoto())
                                <img src="{{ $user->getPhotoUrl() }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-4xl font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold mb-1">{{ $user->name }}</h1>
                            <p class="text-blue-100 text-sm">
                                <i class="fas fa-envelope mr-1"></i> {{ $user->email }}
                            </p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($user->roles as $role)
                                    <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-semibold">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                                <span class="inline-block px-3 py-1 {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }} rounded-full text-xs font-semibold">
                                    {{ $user->is_active ? '✅ Ativo' : '❌ Inativo' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Email</p>
                            <p class="text-gray-900 flex items-center gap-2">
                                <i class="fas fa-envelope text-blue-600"></i>
                                <a href="mailto:{{ $user->email }}" class="hover:text-blue-600">{{ $user->email }}</a>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Telefone</p>
                            <p class="text-gray-900 flex items-center gap-2">
                                <i class="fas fa-phone text-blue-600"></i>
                                @if($user->phone)
                                    <a href="tel:+258{{ $user->phone }}" class="hover:text-blue-600">+258 {{ $user->phone }}</a>
                                @else
                                    <span class="text-gray-400">Não informado</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Email Verificado</p>
                            <p class="text-gray-900">
                                @if($user->email_verified_at)
                                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i> Sim</span>
                                @else
                                    <span class="text-red-600"><i class="fas fa-times-circle mr-1"></i> Não</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Status</p>
                            <p class="text-gray-900">
                                @if($user->is_active)
                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                        <i class="fas fa-check-circle mr-1"></i> Ativo
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                        <i class="fas fa-times-circle mr-1"></i> Inativo
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna Lateral -->
        <div class="space-y-6">
            
            <!-- Informações do Sistema -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i> Informações
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID:</span>
                        <span class="font-mono text-gray-900">#{{ $user->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Criado em:</span>
                        <span class="font-medium text-gray-900">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Última atualização:</span>
                        <span class="font-medium text-gray-900">{{ $user->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-bolt text-yellow-500"></i> Ações Rápidas
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('users.edit', $user->id) }}" 
                       class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">Editar Utilizador</p>
                            <p class="text-xs text-gray-500">Alterar dados e função</p>
                        </div>
                    </a>
                    
                    @if($user->id !== Auth::id())
                        <form method="POST" action="{{ route('users.toggleStatus', $user->id) }}">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('{{ $user->is_active ? 'Desativar' : 'Ativar' }} este utilizador?');"
                                    class="w-full flex items-center gap-3 p-3 bg-amber-50 hover:bg-amber-100 rounded-lg transition text-left">
                                <div class="w-10 h-10 bg-amber-600 rounded-lg flex items-center justify-center text-white">
                                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $user->is_active ? 'Desativar' : 'Ativar' }} Conta</p>
                                    <p class="text-xs text-gray-500">Bloquear ou liberar acesso</p>
                                </div>
                            </button>
                        </form>
                    @else
                        <div class="p-3 bg-gray-100 rounded-lg text-center text-sm text-gray-500">
                            <i class="fas fa-lock mr-1"></i> Não pode editar a sua própria conta aqui
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>