<x-layouts.admin title="Criar Especialidade">

    <!-- Cabeçalho -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Criar Nova Especialidade</h1>
            <p class="text-gray-600">Adicionar uma nova especialidade médica ao sistema</p>
        </div>
        <a href="{{ route('admin.specialties.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-xl shadow-lg transition">
            <i class="fas fa-arrow-left"></i> Voltar à Lista
        </a>
    </div>

    <!-- Formulário -->
    <form method="POST" action="{{ route('admin.specialties.store') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" id="specialtyForm">
        @csrf

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
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
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
                                  placeholder="Breve descrição da especialidade...">{{ old('description') }}</textarea>
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
                    <!-- Ícone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ícone <span class="text-gray-400 text-xs">(Opcional)</span>
                        </label>
                        
                        <!-- Preview do Ícone -->
                        <div class="mb-3">
                            <div id="iconPreview" 
                                 class="w-20 h-20 rounded-xl bg-purple-600 flex items-center justify-center text-white text-3xl shadow-lg mx-auto">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <p class="text-xs text-center text-gray-500 mt-2">Preview do ícone</p>
                        </div>

                        <!-- Select de Ícones Pré-definidos -->
                        <select name="icon" id="iconSelect" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition bg-white mb-3">
                            <option value="">Selecionar ícone...</option>
                            <option value="fa-baby" data-icon="fa-baby" data-color="#3b82f6"> Pediatria</option>
                            <option value="fa-hand-sparkles" data-icon="fa-hand-sparkles" data-color="#f472b6">✨ Dermatologia</option>
                            <option value="fa-comments" data-icon="fa-comments" data-color="#f59e0b">💬 Terapia da Fala</option>
                            <option value="fa-hands-helping" data-icon="fa-hands-helping" data-color="#10b981">🤝 Terapia Ocupacional</option>
                            <option value="fa-stethoscope" data-icon="fa-stethoscope" data-color="#6d28d9">🩺 Clínica Geral</option>
                            <option value="fa-user-md" data-icon="fa-user-md" data-color="#4b5563">👨‍⚕️ Medicina Interna</option>
                            <option value="fa-brain" data-icon="fa-brain" data-color="#8b5cf6">🧠 Psicologia</option>
                            <option value="fa-apple-alt" data-icon="fa-apple-alt" data-color="#84cc16">🍎 Nutrição</option>
                            <option value="fa-female" data-icon="fa-female" data-color="#ec4899">👩 Ginecologia</option>
                            <option value="fa-heartbeat" data-icon="fa-heartbeat" data-color="#ef4444">❤️ Cardiologia</option>
                            <option value="fa-head-side-virus" data-icon="fa-head-side-virus" data-color="#6366f1"> Psiquiatria</option>
                            <option value="fa-briefcase-medical" data-icon="fa-briefcase-medical" data-color="#0ea5e9">💼 Medicina Ocupacional</option>
                            <option value="fa-procedures" data-icon="fa-procedures" data-color="#64748b">🏥 Cirurgia</option>
                            <option value="fa-notes-medical" data-icon="fa-notes-medical" data-color="#06b6d4">📋 Urologia</option>
                        </select>

                        <!-- Campo para Ícone Personalizado -->
                        <div class="relative">
                            <label class="block text-xs text-gray-500 mb-1">
                                Ou insira um ícone personalizado (Font Awesome):
                            </label>
                            <input type="text" name="icon" id="customIcon" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition {{ $errors->has('icon') ? 'border-red-500 bg-red-50' : '' }}" 
                                   placeholder="Ex: fa-heart, fa-lungs, fa-tooth">
                            <p class="text-xs text-gray-500 mt-1">
                                <a href="https://fontawesome.com/v5/search?m=free" target="_blank" class="text-purple-600 hover:underline">
                                    <i class="fas fa-external-link-alt mr-1"></i>Ver todos os ícones Font Awesome
                                </a>
                            </p>
                        </div>
                        @error('icon') <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>

                    <!-- Cor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Cor <span class="text-gray-400 text-xs">(Opcional)</span>
                        </label>
                        
                        <!-- Preview da Cor -->
                        <div class="mb-3">
                            <div id="colorPreview" 
                                 class="w-full h-20 rounded-xl bg-purple-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                #6d28d9
                            </div>
                        </div>

                        <input type="color" name="color" id="color" value="{{ old('color', '#6d28d9') }}" 
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
                           {{ old('is_active', true) ? 'checked' : '' }} 
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
                <i class="fas fa-save"></i> Guardar Especialidade
            </button>
        </div>
    </form>

</x-layouts.admin>

<script>
    // Atualizar preview do ícone quando selecionar do dropdown
    document.getElementById('iconSelect').addEventListener('change', function(e) {
        const selectedOption = e.target.options[e.target.selectedIndex];
        const iconClass = selectedOption.getAttribute('data-icon');
        const color = selectedOption.getAttribute('data-color');
        
        if (iconClass) {
            // Atualizar preview do ícone
            const preview = document.querySelector('#iconPreview i');
            preview.className = 'fas ' + iconClass;
            
            // Atualizar cor do preview
            document.getElementById('iconPreview').style.backgroundColor = color;
            
            // Atualizar campo de cor
            document.getElementById('color').value = color;
            document.getElementById('colorPreview').style.backgroundColor = color;
            document.getElementById('colorPreview').textContent = color;
            
            // Limpar campo de ícone personalizado
            document.getElementById('customIcon').value = '';
        }
    });

    // Atualizar preview quando digitar ícone personalizado
    document.getElementById('customIcon').addEventListener('input', function(e) {
        const iconValue = e.target.value.trim();
        
        if (iconValue) {
            // Atualizar preview do ícone
            const preview = document.querySelector('#iconPreview i');
            preview.className = 'fas ' + iconValue;
            
            // Limpar select
            document.getElementById('iconSelect').value = '';
        }
    });

    // Atualizar preview da cor
    document.getElementById('color').addEventListener('input', function(e) {
        const colorValue = e.target.value;
        document.getElementById('colorPreview').style.backgroundColor = colorValue;
        document.getElementById('colorPreview').textContent = colorValue;
    });

    // Validação do formulário
    document.getElementById('specialtyForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const customIcon = document.getElementById('customIcon').value.trim();
        const iconSelect = document.getElementById('iconSelect').value;
        
        if (name.length < 3) {
            e.preventDefault();
            alert('O nome da especialidade deve ter pelo menos 3 caracteres!');
            return false;
        }
        
        // Se nenhum ícone foi selecionado, usar o do preview
        if (!customIcon && !iconSelect) {
            const previewIcon = document.querySelector('#iconPreview i').className.replace('fas ', '');
            document.getElementById('customIcon').value = previewIcon;
        }
    });
</script>