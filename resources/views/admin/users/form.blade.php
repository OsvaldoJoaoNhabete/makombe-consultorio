<x-layouts.admin title="{{ isset($user) ? 'Editar Utilizador' : 'Novo Utilizador' }}">

    <div class="mb-4">
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-arrow-left"></i> Voltar para utilizadores
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            
            <div class="mb-6 pb-6 border-b">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    {{ isset($user) ? '✏️ Editar Utilizador' : ' Novo Utilizador' }}
                </h1>
                <p class="text-gray-600">
                    {{ isset($user) ? 'Atualize os dados do membro da equipa.' : 'Crie um novo membro da equipa do consultório.' }}
                </p>
            </div>

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <ul class="text-sm text-red-800 space-y-1">
                        @foreach($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" 
                  action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" 
                  enctype="multipart/form-data" 
                  class="space-y-6">
                @csrf
                @if(isset($user))
                    @method('PUT')
                @endif

                <!-- Foto de Perfil -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-camera text-blue-600"></i> Foto de Perfil
                    </h3>
                    
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="relative">
                            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                @if(isset($user) && $user->hasPhoto())
                                    <img id="photoPreview" src="{{ $user->getPhotoUrl() }}" alt="Foto" class="w-full h-full object-cover">
                                @else
                                    <div id="photoPreview" class="w-full h-full flex items-center justify-center text-white text-4xl font-bold">
                                        {{ isset($user) ? strtoupper(substr($user->name, 0, 1)) : '?' }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex-1 w-full">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Escolher foto</label>
                            <input type="file" name="photo" id="photoInput" accept="image/*"
                                   class="hidden" onchange="previewPhoto(this)">
                            <label for="photoInput" 
                                   class="flex items-center justify-center gap-2 px-4 py-3 bg-white border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition">
                                <i class="fas fa-cloud-upload-alt text-blue-600"></i>
                                <span class="text-sm text-gray-700 font-medium">Clique para selecionar imagem</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i> Formatos: JPG, PNG. Tamanho máximo: 2MB
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Dados Pessoais -->
                <div class="border-b pb-4 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-user text-blue-600"></i> Dados Pessoais
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nome Completo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ex: Dr. João Silva">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="staff@makombe.co.mz">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Telefone
                            </label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm">+258</span>
                                <input type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-r-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="841234567">
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Função <span class="text-red-500">*</span>
                            </label>
                            <select name="role" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecione uma função...</option>
                                @foreach($roles as $r)
                                    @php
                                        $roleIcons = [
                                            'Administrador' => '',
                                            'Gerente' => '',
                                            'Medico' => '‍⚕️',
                                            'Enfermeiro' => '👩‍️',
                                            'Atendente' => '🧑‍💼',
                                            'Financeiro' => '💰',
                                        ];
                                    @endphp
                                    <option value="{{ $r->name }}" {{ old('role', isset($user) ? $user->roles->first()?->name : '') === $r->name ? 'selected' : '' }}>
                                        {{ $roleIcons[$r->name] ?? '' }} {{ $r->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Senha -->
                <div class="border-b pb-4 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-lock text-green-600"></i> 
                        {{ isset($user) ? 'Alterar Senha (Opcional)' : 'Senha de Acesso' }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Senha {{ isset($user) ? '(deixe em branco para manter)' : '*' }}
                            </label>
                            <input type="password" name="password" 
                                   {{ isset($user) ? '' : 'required' }}
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Mínimo 6 caracteres">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Confirmar Senha
                            </label>
                            <input type="password" name="password_confirmation" 
                                   {{ isset($user) ? '' : 'required' }}
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Repita a senha">
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
                    <button type="submit" 
                            class="flex-1 py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> {{ isset($user) ? 'Atualizar' : 'Criar' }} Utilizador
                    </button>
                    <a href="{{ route('users.index') }}" 
                       class="flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewPhoto(input) {
            const preview = document.getElementById('photoPreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        preview.parentElement.innerHTML = '<img id="photoPreview" src="' + e.target.result + '" alt="Foto" class="w-full h-full object-cover">';
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</x-layouts.admin>