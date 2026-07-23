<x-layouts.admin title="Editar Especialidade">

    <!-- Cabeçalho -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Editar Especialidade</h1>
            <p class="text-gray-600">Atualizar informações de {{ $specialty->name }}</p>
        </div>
        <a href="{{ route('admin.specialties.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-xl shadow-lg transition">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <!-- Formulário -->
    <form method="POST" action="{{ route('admin.specialties.update', $specialty->id) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" id="specialtyForm">
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
            
            <!-- Dados Básicos -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-purple-600"></i> Dados Básicos
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nome da Especialidade <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $specialty->name) }}" 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition {{ $errors->has('name') ? 'border-red-500 bg-red-50' : '' }}" 
                               placeholder="Ex: Cardiologia, Pediatria, Ortopedia" required>
                        @error('name') <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Descrição <span class="text-gray-400 text-xs">(Opcional)</span>
                        </label>
                        <textarea name="description" id="description" rows="3" 
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition {{ $errors->has('description') ? 'border-red-500 bg-red-50' : '' }}" 
                                  placeholder="Breve descrição da especialidade...">{{ old('description', $specialty->description) }}</textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- Personalização Visual -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-palette text-purple-600"></i> Personalização Visual
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ícone <span class="text-gray-400 text-xs">(Opcional)</span>
                        </label>
                        <select name="icon" id="icon" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition bg-white">
                            <option value="">Selecionar ícone...</option>
                            <option value="fa-heart" {{ old('icon', $specialty->icon) == 'fa-heart' ? 'selected' : '' }}>❤️ Coração (Cardiologia)</option>
                            <option value="fa-brain" {{ old('icon', $specialty->icon) == 'fa-brain' ? 'selected' : '' }}>🧠 Cérebro (Neurologia)</option>
                            <option value="fa-bone" {{ old('icon', $specialty->icon) == 'fa-bone' ? 'selected' : '' }}>🦴 Osso (Ortopedia)</option>
                            <option value="fa-baby" {{ old('icon', $specialty->icon) == 'fa-baby' ? 'selected' : '' }}>👶 Bebé (Pediatria)</option>
                            <option value="fa-eye" {{ old('icon', $specialty->icon) == 'fa-eye' ? 'selected' : '' }}>️ Olho (Oftalmologia)</option>
                            <option value="fa-tooth" {{ old('icon', $specialty->icon) == 'fa-tooth' ? 'selected' : '' }}> Dente (Odontologia)</option>
                            <option value="fa-lungs" {{ old('icon', $specialty->icon) == 'fa-lungs' ? 'selected' : '' }}>🫁 Pulmão (Pneumologia)</option>
                            <option value="fa-stethoscope" {{ old('icon', $specialty->icon) == 'fa-stethoscope' ? 'selected' : '' }}> Estetoscópio (Clínica Geral)</option>
                        </select>
                        @error('icon') <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Cor <span class="text-gray-400 text-xs">(Opcional)</span>
                        </label>
                        <input type="color" name="color" id="color" value="{{ old('color', $specialty->color ?? '#6d28d9') }}" 
                               class="w-full h-12 px-2 border border-gray-300 rounded-lg cursor-pointer {{ $errors->has('color') ? 'border-red-500 bg-red-50' : '' }}">
                        @error('color') <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- Estado -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-cog text-purple-600"></i> Estado
                </h3>
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                           {{ old('is_active', $specialty->is_active) ? 'checked' : '' }} 
                           class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700">
                        Especialidade ativa e disponível para seleção
                    </label>
                </div>
            </div>

        </div>

        <!-- Botões de Ação -->
        <div class="bg-gray-50 px-6 md:px-8 py-5 flex items-center justify-end gap-4 border-t border-gray-100">
            <a href="{{ route('admin.specialties.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-8 py-2.5 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700 shadow-md transition-all transform hover:scale-105 flex items-center gap-2">
                <i class="fas fa-save"></i> Guardar Alterações
            </button>
        </div>
    </form>

</x-layouts.admin>

<script>
    document.getElementById('specialtyForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        
        if (name.length < 3) {
            e.preventDefault();
            alert('O nome da especialidade deve ter pelo menos 3 caracteres!');
            return false;
        }
    });
</script>