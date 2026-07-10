<x-layouts.admin title="Gestão da Secção 'Sobre Nós'">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">ℹ️ Gestão da Secção "Sobre Nós"</h1>
            <p class="text-gray-600">Edite todos os elementos que aparecem na secção "Sobre Nós" da landing page.</p>
        </div>
        <a href="{{ route('admin.content.index') }}" class="text-blue-600 hover:underline"><i class="fas fa-arrow-left mr-1"></i> Voltar</a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 flex items-center gap-3">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
            <h4 class="font-bold text-red-800 mb-2">❌ Erros de validação:</h4>
            <ul class="text-sm text-red-800 space-y-1">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.content.about.update') }}" class="space-y-6">
        @csrf

        <!-- IMAGEM -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-image text-blue-600"></i> Imagem da Secção
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Imagem Atual:</label>
                    <div class="bg-gray-100 rounded-xl overflow-hidden border-2 border-dashed border-gray-300 h-64 flex items-center justify-center">
                        @php
                            $aboutImagePath = \App\Models\SiteSetting::get('about_image_path');
                        @endphp
                        @if($aboutImagePath && file_exists(storage_path('app/public/' . $aboutImagePath)))
                            <img src="{{ asset('storage/' . $aboutImagePath) }}" alt="Sobre Nós" class="w-full h-full object-cover">
                        @else
                            <div class="text-center text-gray-400">
                                <i class="fas fa-image text-5xl mb-2"></i>
                                <p class="text-sm">Nenhuma imagem carregada</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alterar Imagem:</label>
                    <form method="POST" action="{{ route('admin.content.settings.uploadAboutImage') }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <input type="file" name="about_image" required accept="image/jpeg,image/png,image/jpg" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500">Formatos: JPG, PNG. Recomendado: 800x600px</p>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-upload mr-1"></i> Carregar Nova Imagem
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- TÍTULOS -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-heading text-purple-600"></i> Títulos
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Subtítulo</label>
                    <input type="text" name="about_subtitle" 
                           value="{{ old('about_subtitle', $settings['about_subtitle'] ?? 'Sobre Nós') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Título Principal</label>
                    <input type="text" name="about_title" 
                           value="{{ old('about_title', $settings['about_title'] ?? 'Excelência em cuidados médicos em Moçambique') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
            </div>
        </div>

        <!-- PARÁGRAFOS -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-paragraph text-indigo-600"></i> Textos Descritivos
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Primeiro Parágrafo</label>
                    <textarea name="about_paragraph_1" rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('about_paragraph_1', $settings['about_paragraph_1'] ?? 'O Makombe Consultório Médico é referência em atendimento de qualidade em Maputo. Com uma equipa multidisciplinar de profissionais altamente qualificados, oferecemos cuidados personalizados para cada paciente.') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Segundo Parágrafo</label>
                    <textarea name="about_paragraph_2" rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('about_paragraph_2', $settings['about_paragraph_2'] ?? 'Nossa missão é proporcionar saúde acessível, humana e de excelência, utilizando tecnologia moderna e seguindo os mais altos padrões éticos e profissionais.') }}</textarea>
                </div>
            </div>
        </div>

        <!-- CARD FLUTUANTE -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-award text-amber-600"></i> Card Flutuante
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Número/Texto Grande</label>
                    <input type="text" name="about_card_number" 
                           value="{{ old('about_card_number', $settings['about_card_number'] ?? '10+') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Descrição do Card</label>
                    <input type="text" name="about_card_text" 
                           value="{{ old('about_card_text', $settings['about_card_text'] ?? 'Anos de experiência') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
            </div>
        </div>

        <!-- CARACTERÍSTICAS -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-600"></i> Características
            </h3>
            
            <div class="space-y-6">
                @for($i = 1; $i <= 4; $i++)
                    <div class="p-4 bg-{{ ['emerald', 'blue', 'purple', 'amber'][$i-1] }}-50 rounded-lg border border-{{ ['emerald', 'blue', 'purple', 'amber'][$i-1] }}-200">
                        <p class="text-sm font-bold text-{{ ['emerald', 'blue', 'purple', 'amber'][$i-1] }}-800 mb-3">Característica {{ $i }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Título</label>
                                <input type="text" name="feature_{{ $i }}_title" 
                                       value="{{ old('feature_' . $i . '_title', $settings['feature_' . $i . '_title'] ?? '') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-{{ ['emerald', 'blue', 'purple', 'amber'][$i-1] }}-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Descrição</label>
                                <input type="text" name="feature_{{ $i }}_desc" 
                                       value="{{ old('feature_' . $i . '_desc', $settings['feature_' . $i . '_desc'] ?? '') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-{{ ['emerald', 'blue', 'purple', 'amber'][$i-1] }}-500">
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Botão Salvar -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.content.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">
                <i class="fas fa-times mr-1"></i> Cancelar
            </a>
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-lg hover:from-emerald-700 hover:to-teal-700 transition shadow-lg flex items-center gap-2">
                <i class="fas fa-save"></i>
                <span>Salvar Todas as Alterações</span>
            </button>
        </div>
    </form>
</x-layouts.admin>