<x-layouts.admin title="Configurações Gerais">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">⚙️ Configurações do Site</h1>
            <p class="text-gray-600">Informações gerais, redes sociais e imagem "Sobre Nós".</p>
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
            <h4 class="font-bold text-red-800 mb-2">❌ Erros:</h4>
            <ul class="text-sm text-red-800 space-y-1">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Formulário de Configurações -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-cog text-blue-600"></i> Informações Gerais
            </h3>
            <form method="POST" action="{{ route('admin.content.settings.update') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nome do Site</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Makombe Consultório Médico' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Slogan</label>
                    <input type="text" name="site_slogan" value="{{ $settings['site_slogan'] ?? 'Aqui você tem saúde' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Telefone Principal</label>
                        <input type="text" name="phone_main" value="{{ $settings['phone_main'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email Principal</label>
                        <input type="email" name="email_main" value="{{ $settings['email_main'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Endereço Completo</label>
                    <textarea name="address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg">{{ $settings['address'] ?? '' }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">WhatsApp (número)</label>
                        <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Facebook URL</label>
                        <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Instagram URL</label>
                    <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <button type="submit" class="w-full px-6 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Salvar Configurações
                </button>
            </form>
        </div>

        <!-- Upload da Imagem "Sobre Nós" -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-image text-purple-600"></i> Imagem "Sobre Nós"
            </h3>
            
            <!-- Imagem Atual -->
            <div class="mb-4">
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

            <!-- Formulário de Upload -->
            <form method="POST" action="{{ route('admin.content.settings.uploadAboutImage') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nova Imagem (Max 2MB)</label>
                    <input type="file" name="about_image" required accept="image/jpeg,image/png,image/jpg" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                           onchange="previewAboutImage(this)">
                    <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG. Recomendado: 800x600px</p>
                </div>
                
                <!-- Preview -->
                <div id="aboutPreview" class="hidden">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pré-visualização:</label>
                    <div class="bg-gray-100 rounded-xl overflow-hidden border-2 border-purple-200 h-48">
                        <img id="aboutPreviewImg" src="" class="w-full h-full object-cover">
                    </div>
                </div>

                <button type="submit" class="w-full px-6 py-2.5 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700 transition flex items-center justify-center gap-2">
                    <i class="fas fa-upload"></i> Carregar Imagem
                </button>
            </form>

            <!-- Instruções -->
            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-xs text-blue-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong>Dica:</strong> Esta imagem aparece na secção "Sobre Nós" da landing page. Use uma imagem de alta qualidade que represente o consultório ou a equipa.
                </p>
            </div>
        </div>
    </div>

    <script>
        function previewAboutImage(input) {
            const preview = document.getElementById('aboutPreview');
            const previewImg = document.getElementById('aboutPreviewImg');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-layouts.admin>