<x-layouts.admin title="Criar Seguradora">

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Criar Nova Seguradora</h1>
            <p class="text-gray-600">Registar uma nova entidade seguradora ou plano de saúde</p>
        </div>
        <a href="{{ route('insurances.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-xl shadow-lg transition">
            <i class="fas fa-arrow-left"></i> Voltar à Lista
        </a>
    </div>

    <form method="POST" action="{{ route('insurances.store') }}" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" id="insuranceForm">
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
            <!-- Dados Principais -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-building text-purple-600"></i> Dados Principais</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome da Seguradora <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition {{ $errors->has('name') ? 'border-red-500 bg-red-50' : '' }}" placeholder="Ex: AMS, União Seguros, ENI" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Código / Sigla <span class="text-gray-400 text-xs">(Opcional)</span></label>
                        <input type="text" name="code" value="{{ old('code') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition uppercase" placeholder="Ex: AMS">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Percentagem de Cobertura <span class="text-gray-400 text-xs">(Opcional)</span></label>
                        <div class="relative">
                            <input type="number" name="coverage_percentage" value="{{ old('coverage_percentage') }}" min="0" max="100" step="0.01" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition" placeholder="Ex: 80">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500">%</span>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- Contactos -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-address-book text-purple-600"></i> Contactos</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pessoa de Contacto</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition" placeholder="Ex: 84 123 4567">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Endereço</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition" placeholder="Endereço físico da sede ou delegação">
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- Logótipo e Estado -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-image text-purple-600"></i> Logótipo e Estado</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logótipo <span class="text-gray-400 text-xs">(Opcional)</span></label>
                        <div class="flex items-center gap-4">
                            <img id="logoPreview" src="https://ui-avatars.com/api/?name=Seguradora&background=6d28d9&color=fff&size=128" class="w-20 h-20 rounded-lg object-cover border border-gray-200 bg-white">
                            <div class="flex-1">
                                <input type="file" name="logo_path" id="logoInput" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition">
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG ou WEBP. Máx: 2MB.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <label for="is_active" class="text-sm font-medium text-gray-700">Seguradora ativa e disponível para seleção</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 px-6 md:px-8 py-5 flex items-center justify-end gap-4 border-t border-gray-100">
            <a href="{{ route('insurances.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Cancelar</a>
            <button type="submit" class="px-8 py-2.5 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700 shadow-md transition-all transform hover:scale-105 flex items-center gap-2">
                <i class="fas fa-save"></i> Guardar Seguradora
            </button>
        </div>
    </form>

    <script>
        document.getElementById('logoInput').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) { document.getElementById('logoPreview').src = e.target.result; };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>
</x-layouts.admin>