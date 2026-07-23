<x-layouts.admin title="Editar Utilizador">

    <!-- Cabeçalho -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Editar Utilizador</h1>
            <p class="text-gray-600">Atualizar informações de {{ $user->name }}</p>
        </div>
        <a href="{{ route('users.show', $user->id) }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-xl shadow-lg transition">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <!-- Formulário -->
    <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" id="userForm">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="mx-6 mt-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-800">
                            Por favor, corrija os seguintes erros:
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="p-6 md:p-8 space-y-8">
            
            <!-- Dados Pessoais -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-purple-600"></i> Dados Pessoais
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nome Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition {{ $errors->has('name') ? 'border-red-500 bg-red-50' : '' }}" 
                               placeholder="Ex: Osvaldo Nhabete" required>
                        @error('name') <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Endereço de E-mail <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition {{ $errors->has('email') ? 'border-red-500 bg-red-50' : '' }}" 
                               placeholder="exemplo@makombe.co.mz" required>
                        @error('email') <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Telefone / Telemóvel
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition {{ $errors->has('phone') ? 'border-red-500 bg-red-50' : '' }}" 
                               placeholder="Ex: 84 123 4567" maxlength="20">
                        @error('phone') <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- Segurança e Acesso -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-lock text-purple-600"></i> Segurança e Acesso
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nova Palavra-passe <span class="text-gray-400 text-xs">(Deixe em branco para manter)</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="password" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition {{ $errors->has('password') ? 'border-red-500 bg-red-50' : '' }}" 
                                   placeholder="Mínimo 8 caracteres">
                            <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-purple-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password') <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Nova Palavra-passe
                        </label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition" 
                                   placeholder="Repita a nova palavra-passe">
                            <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-purple-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Função no Sistema <span class="text-red-500">*</span>
                        </label>
                        <select name="role" id="role" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition bg-white {{ $errors->has('role') ? 'border-red-500 bg-red-50' : '' }}" 
                                required>
                            @foreach($roles as $r)
                                <option value="{{ $r->name }}" {{ old('role', $user->roles->first()->name) == $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                            @endforeach
                        </select>
                        @error('role') <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Especialidade <span class="text-gray-400 text-xs">(Opcional)</span>
                        </label>
                        <select name="specialty_id" id="specialty_id" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition bg-white">
                            <option value="">Nenhuma / Não se aplica</option>
                            @foreach($specialties as $spec)
                                <option value="{{ $spec->id }}" {{ old('specialty_id', $user->specialty_id) == $spec->id ? 'selected' : '' }}>{{ $spec->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- Estado e Foto -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-cog text-purple-600"></i> Estado e Foto
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center gap-3 mt-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               {{ old('is_active', $user->is_active) ? 'checked' : '' }} 
                               class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <label for="is_active" class="text-sm font-medium text-gray-700">
                            Utilizador ativo
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto de Perfil <span class="text-gray-400 text-xs">(Opcional)</span>
                        </label>
                        @if($user->photo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto atual" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                                <p class="text-xs text-gray-500 mt-1">Foto atual</p>
                            </div>
                        @endif
                        <input type="file" name="photo" id="photo" accept="image/*" 
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition">
                        <p class="mt-1 text-xs text-gray-500">Nova foto (substitui a atual)</p>
                        @error('photo') <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

        </div>

        <!-- Botões de Ação -->
        <div class="bg-gray-50 px-6 md:px-8 py-5 flex items-center justify-end gap-4 border-t border-gray-100">
            <a href="{{ route('users.show', $user->id) }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-8 py-2.5 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700 shadow-md transition-all transform hover:scale-105 flex items-center gap-2">
                <i class="fas fa-save"></i> Guardar Alterações
            </button>
        </div>
    </form>

</x-layouts.admin>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.nextElementSibling.querySelector('i');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    document.getElementById('userForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirmation').value;
        
        if (password && password !== passwordConfirm) {
            e.preventDefault();
            alert('As palavras-passe não coincidem!');
            return false;
        }
        
        if (password && password.length < 8) {
            e.preventDefault();
            alert('A palavra-passe deve ter pelo menos 8 caracteres!');
            return false;
        }
    });

    document.getElementById('photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.size > 2 * 1024 * 1024) {
            alert('A imagem não pode exceder 2MB!');
            e.target.value = '';
        }
    });
</script>