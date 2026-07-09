<x-layouts.admin title="{{ isset($insurance) ? 'Editar Seguradora' : 'Nova Seguradora' }}">

    <div class="mb-4">
        <a href="{{ route('insurances.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-arrow-left"></i> Voltar para seguradoras
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            
            <div class="mb-6 pb-6 border-b">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    {{ isset($insurance) ? '✏️ Editar Seguradora' : '🛡️ Nova Seguradora' }}
                </h1>
                <p class="text-gray-600">
                    {{ isset($insurance) ? 'Atualize os dados da seguradora.' : 'Cadastre uma nova seguradora parceira do consultório.' }}
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
                  action="{{ isset($insurance) ? route('insurances.update', $insurance->id) : route('insurances.store') }}" 
                  enctype="multipart/form-data" 
                  class="space-y-6">
                @csrf
                @if(isset($insurance))
                    @method('PUT')
                @endif

                <!-- Logo -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-image text-blue-600"></i> Logo da Seguradora
                    </h3>
                    
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="relative">
                            <div class="w-32 h-32 rounded-xl overflow-hidden border-4 border-white shadow-lg bg-white flex items-center justify-center">
                                @if(isset($insurance) && $insurance->logo_path)
                                    <img id="logoPreview" src="{{ $insurance->getLogoUrl() }}" alt="Logo" class="w-full h-full object-contain p-2">
                                @else
                                    <div id="logoPreview" class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i class="fas fa-shield-alt text-4xl"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex-1 w-full">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Escolher logo</label>
                            <input type="file" name="logo" id="logoInput" accept="image/*"
                                   class="hidden" onchange="previewLogo(this)">
                            <label for="logoInput" 
                                   class="flex items-center justify-center gap-2 px-4 py-3 bg-white border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition">
                                <i class="fas fa-cloud-upload-alt text-blue-600"></i>
                                <span class="text-sm text-gray-700 font-medium">Clique para selecionar imagem</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i> Formatos: JPG, PNG, SVG. Tamanho máximo: 2MB
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Dados Principais -->
                <div class="border-b pb-4 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-building text-blue-600"></i> Dados Principais
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nome da Seguradora <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $insurance->name ?? '') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ex: Millennium BIM, FNB Seguros, etc.">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Código
                            </label>
                            <input type="text" name="code" value="{{ old('code', $insurance->code ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ex: MIL, FNB, etc.">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Percentagem de Cobertura (%)
                            </label>
                            <input type="number" name="coverage_percentage" step="0.01" min="0" max="100"
                                   value="{{ old('coverage_percentage', $insurance->coverage_percentage ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ex: 80">
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i> Percentagem que a seguradora cobre (0-100%)
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contacto -->
                <div class="border-b pb-4 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-address-book text-blue-600"></i> Contacto
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Pessoa de Contacto
                            </label>
                            <input type="text" name="contact_person" value="{{ old('contact_person', $insurance->contact_person ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Nome do representante">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" name="email" value="{{ old('email', $insurance->email ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="contacto@seguradora.co.mz">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Telefone
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', $insurance->phone ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="+258 21 123 456">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Endereço
                            </label>
                            <input type="text" name="address" value="{{ old('address', $insurance->address ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Av. 25 de Setembro, Maputo">
                        </div>
                    </div>
                </div>

                <!-- Observações -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-sticky-note text-amber-600"></i> Observações
                    </h3>
                    <textarea name="notes" rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Informações adicionais sobre a seguradora, procedimentos, etc.">{{ old('notes', $insurance->notes ?? '') }}</textarea>
                </div>

                <!-- Botões -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
                    <button type="submit" 
                            class="flex-1 py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> {{ isset($insurance) ? 'Atualizar' : 'Cadastrar' }} Seguradora
                    </button>
                    <a href="{{ route('insurances.index') }}" 
                       class="flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewLogo(input) {
            const preview = document.getElementById('logoPreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        preview.parentElement.innerHTML = '<img id="logoPreview" src="' + e.target.result + '" alt="Logo" class="w-full h-full object-contain p-2">';
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</x-layouts.admin>