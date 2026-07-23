<x-layouts.admin title="Criar Novo Utilizador">

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Criar Novo Utilizador</h1>
            <p class="text-gray-600">Preencha os dados para adicionar um novo membro à equipa</p>
        </div>
        <a href="{{ route('users.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-xl shadow-lg transition">
            <i class="fas fa-arrow-left"></i> Voltar à Lista
        </a>
    </div>

    <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" id="userForm">
        @csrf

        @if ($errors->any())
            <div class="mx-6 mt-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                <h3 class="text-sm font-bold text-red-800 mb-2">Por favor, corrija os seguintes erros:</h3>
                <ul class="list-disc pl-5 text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome Completo <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition" placeholder="Ex: Osvaldo Nhabete" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Endereço de E-mail <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition" placeholder="exemplo@makombe.co.mz" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Telefone / Telemóvel</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition" placeholder="Ex: 84 123 4567" maxlength="20">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Palavra-passe <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition" placeholder="Mínimo 8 caracteres" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Palavra-passe <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition" placeholder="Repita a palavra-passe" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Função no Sistema <span class="text-red-500">*</span></label>
                        <select name="role" id="role" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition bg-white" required>
                            <option value="">Selecione uma função...</option>
                            @foreach($roles as $r)
                                <option value="{{ $r->name }}" {{ old('role') == $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Especialidade <span class="text-gray-400 text-xs">(Opcional)</span></label>
                        <select name="specialty_id" id="specialty_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition bg-white">
                            <option value="">Nenhuma / Não se aplica</option>
                            @foreach($specialties as $spec)
                                <option value="{{ $spec->id }}" {{ old('specialty_id') == $spec->id ? 'selected' : '' }}>{{ $spec->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- Disponibilidade do Profissional -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-purple-600"></i> Disponibilidade Semanal
                </h3>
                <p class="text-sm text-gray-600 mb-4">Registe os dias e horários em que o profissional está disponível para atender</p>
                
                <div id="availabilityContainer" class="space-y-4">
                    <!-- Linha de exemplo -->
                    <div class="availability-row bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Dia da Semana</label>
                                <select name="availabilities[0][day_of_week]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    <option value="monday">Segunda-feira</option>
                                    <option value="tuesday">Terça-feira</option>
                                    <option value="wednesday">Quarta-feira</option>
                                    <option value="thursday">Quinta-feira</option>
                                    <option value="friday">Sexta-feira</option>
                                    <option value="saturday">Sábado</option>
                                    <option value="sunday">Domingo</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Hora de Início</label>
                                <input type="time" name="availabilities[0][start_time]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Hora de Fim</label>
                                <input type="time" name="availabilities[0][end_time]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                            </div>
                            <div class="flex items-end">
                                <button type="button" onclick="removeAvailability(this)" class="px-4 py-2 bg-red-50 text-red-600 rounded-lg text-sm hover:bg-red-100 transition">
                                    <i class="fas fa-trash"></i> Remover
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="addAvailability()" class="mt-4 px-4 py-2 bg-purple-50 text-purple-700 rounded-lg text-sm font-medium hover:bg-purple-100 transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Adicionar Outro Horário
                </button>
            </div>

            <hr class="border-gray-100">

            <!-- Estado e Foto -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-cog text-purple-600"></i> Estado e Foto
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center gap-3 mt-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <label for="is_active" class="text-sm font-medium text-gray-700">Utilizador ativo imediatamente após a criação</label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto de Perfil <span class="text-gray-400 text-xs">(Opcional)</span></label>
                        <input type="file" name="photo" id="photo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition">
                        <p class="mt-1 text-xs text-gray-500">Formatos: JPG, PNG, GIF, WEBP. Máximo: 2MB.</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="bg-gray-50 px-6 md:px-8 py-5 flex items-center justify-end gap-4 border-t border-gray-100">
            <a href="{{ route('users.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Cancelar</a>
            <button type="submit" class="px-8 py-2.5 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700 shadow-md transition-all transform hover:scale-105 flex items-center gap-2">
                <i class="fas fa-save"></i> Guardar Utilizador
            </button>
        </div>
    </form>

</x-layouts.admin>

<script>
    let availabilityIndex = 1;

    function addAvailability() {
        const container = document.getElementById('availabilityContainer');
        const newRow = document.createElement('div');
        newRow.className = 'availability-row bg-gray-50 p-4 rounded-lg border border-gray-200 mt-4';
        newRow.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Dia da Semana</label>
                    <select name="availabilities[${availabilityIndex}][day_of_week]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="monday">Segunda-feira</option>
                        <option value="tuesday">Terça-feira</option>
                        <option value="wednesday">Quarta-feira</option>
                        <option value="thursday">Quinta-feira</option>
                        <option value="friday">Sexta-feira</option>
                        <option value="saturday">Sábado</option>
                        <option value="sunday">Domingo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Hora de Início</label>
                    <input type="time" name="availabilities[${availabilityIndex}][start_time]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Hora de Fim</label>
                    <input type="time" name="availabilities[${availabilityIndex}][end_time]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                </div>
                <div class="flex items-end">
                    <button type="button" onclick="removeAvailability(this)" class="px-4 py-2 bg-red-50 text-red-600 rounded-lg text-sm hover:bg-red-100 transition">
                        <i class="fas fa-trash"></i> Remover
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newRow);
        availabilityIndex++;
    }

    function removeAvailability(button) {
        button.closest('.availability-row').remove();
    }

    // Validação do formulário
    document.getElementById('userForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirmation').value;
        
        if (password !== passwordConfirm) {
            e.preventDefault();
            alert('As palavras-passe não coincidem!');
            return false;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            alert('A palavra-passe deve ter pelo menos 8 caracteres!');
            return false;
        }
    });
</script>