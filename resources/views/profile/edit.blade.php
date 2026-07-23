<x-layouts.admin title="Meu Perfil">

    <!-- Cabeçalho -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Meu Perfil</h1>
            <p class="text-gray-600">Gerir as suas informações pessoais e segurança</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
            <p class="text-sm text-green-700 font-medium">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
            <h3 class="text-sm font-bold text-red-800 mb-2">
                Por favor, corrija os seguintes erros:
            </h3>
            <div class="text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Coluna Esquerda: Foto e Resumo -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Foto de Perfil</h3>
                
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="photoForm">
                    @csrf
                    @method('PATCH')
                    
                    <div class="relative w-40 h-40 mx-auto mb-4 group">
                        @if(Auth::user()->photo)
                            <img id="photoPreview" src="{{ asset('storage/' . Auth::user()->photo) }}" 
                                 alt="Foto de Perfil" 
                                 class="w-full h-full rounded-full object-cover border-4 border-purple-200 shadow-lg">
                        @else
                            <div id="photoPreview" 
                                 class="w-full h-full rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white text-5xl font-bold border-4 border-purple-200 shadow-lg">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        
                        <!-- Overlay para upload -->
                        <div class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            <i class="fas fa-camera text-white text-2xl"></i>
                        </div>
                    </div>

                    <input type="file" name="photo" id="photoInput" accept="image/*" 
                           class="hidden" onchange="previewPhoto(this)">
                    
                    <button type="button" onclick="document.getElementById('photoInput').click()" 
                            class="w-full mb-3 px-4 py-2 bg-purple-50 text-purple-700 font-medium rounded-lg hover:bg-purple-100 transition flex items-center justify-center gap-2">
                        <i class="fas fa-upload"></i> Escolher Foto
                    </button>
                    
                    <button type="submit" id="savePhotoBtn" 
                            class="w-full px-4 py-2 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700 transition hidden">
                        <i class="fas fa-save"></i> Guardar Foto
                    </button>
                    
                    <p class="text-xs text-gray-500 mt-2">JPG, PNG, GIF ou WEBP. Máx: 2MB</p>
                </form>

                <div class="border-t border-gray-100 pt-4 mt-4 space-y-3 text-left">
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="fas fa-user-shield text-purple-500 w-5"></i>
                        <span>{{ Auth::user()->roles->pluck('name')->first() ?? 'Utilizador' }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="fas fa-envelope text-purple-500 w-5"></i>
                        <span class="truncate">{{ Auth::user()->email }}</span>
                    </div>
                    @if(Auth::user()->phone)
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="fas fa-phone text-purple-500 w-5"></i>
                        <span>+258 {{ Auth::user()->phone }}</span>
                    </div>
                    @endif
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="fas fa-calendar text-purple-500 w-5"></i>
                        <span>Membro desde {{ Auth::user()->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna Direita: Formulários -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Formulário: Dados Pessoais -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-id-card text-purple-600"></i> Dados Pessoais
                </h3>
                
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nome Completo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition {{ $errors->has('name') ? 'border-red-500 bg-red-50' : '' }}" 
                                   placeholder="Ex: Osvaldo Nhabete" required>
                            @error('name') <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Endereço de E-mail <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition {{ $errors->has('email') ? 'border-red-500 bg-red-50' : '' }}" 
                                   placeholder="exemplo@makombe.co.mz" required>
                            @error('email') <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Telefone / Telemóvel
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition {{ $errors->has('phone') ? 'border-red-500 bg-red-50' : '' }}" 
                                   placeholder="Ex: 84 123 4567" maxlength="20">
                            @error('phone') <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-8 py-2.5 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700 shadow-md transition flex items-center gap-2">
                            <i class="fas fa-save"></i> Guardar Dados
                        </button>
                    </div>
                </form>
            </div>

            <!-- Formulário: Segurança -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-shield-alt text-purple-600"></i> Alterar Palavra-passe
                </h3>
                <p class="text-sm text-gray-600 mb-4">Deixe em branco se não desejar alterar a palavra-passe</p>
                
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nova Palavra-passe
                            </label>
                            <input type="password" name="password" id="password" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition {{ $errors->has('password') ? 'border-red-500 bg-red-50' : '' }}" 
                                   placeholder="Mínimo 8 caracteres">
                            @error('password') <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Palavra-passe
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition" 
                                   placeholder="Repita a palavra-passe">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-8 py-2.5 bg-gray-800 text-white font-bold rounded-lg hover:bg-gray-900 shadow-md transition flex items-center gap-2">
                            <i class="fas fa-key"></i> Atualizar Palavra-passe
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</x-layouts.admin>

<script>
    // Preview da foto antes de guardar
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validar tamanho
            if (file.size > 2 * 1024 * 1024) {
                alert('A imagem não pode exceder 2MB!');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photoPreview');
                preview.src = e.target.result;
                preview.classList.remove('bg-gradient-to-br', 'from-purple-500', 'to-indigo-600');
                preview.classList.add('object-cover');
                
                // Mostrar botão de guardar
                document.getElementById('savePhotoBtn').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
</script>