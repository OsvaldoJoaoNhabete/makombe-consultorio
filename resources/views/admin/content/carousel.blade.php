<x-layouts.admin title="Gerir Carrossel">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">🖼️ Imagens do Carrossel</h1>
            <p class="text-gray-600">Adicione as imagens que aparecem no topo da página inicial.</p>
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

    <!-- Formulário de Adição -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-4">📤 Adicionar Nova Imagem</h3>
        <form method="POST" action="{{ route('admin.content.carousel.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Título (Opcional)</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Ex: Consultório Médico" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Ordem</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Descrição (Opcional)</label>
                <textarea name="description" rows="2" placeholder="Descrição da imagem..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Imagem (Max 5MB) <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-4">
                    <input type="file" name="image" id="carouselImage" required accept="image/jpeg,image/png,image/jpg"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           onchange="previewImage(this)">
                </div>
                <p class="text-xs text-gray-500 mt-1">Formatos aceites: JPG, PNG. Tamanho máximo: 5MB</p>
                
                <!-- Preview da imagem -->
                <div id="imagePreview" class="mt-3 hidden">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Pré-visualização:</p>
                    <img id="previewImg" src="" alt="Preview" class="max-w-md h-32 object-cover rounded-lg border border-gray-300">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition flex items-center gap-2 shadow-md">
                    <i class="fas fa-plus-circle"></i> 
                    <span>Adicionar Imagem ao Carrossel</span>
                </button>
                <button type="reset" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition">
                    <i class="fas fa-undo mr-1"></i> Limpar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Imagens Existentes -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h3 class="font-bold text-gray-900">📋 Imagens Existentes ({{ $images->count() }})</h3>
        </div>
        
        @if($images->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Imagem</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ordem</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($images as $img)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    @if($img->image_path && file_exists(storage_path('app/public/' . $img->image_path)))
                                        <img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $img->title }}" 
                                             class="w-24 h-16 object-cover rounded-lg border border-gray-200 shadow-sm">
                                    @else
                                        <div class="w-24 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                                            <i class="fas fa-image text-2xl"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">{{ $img->title ?? 'Sem título' }}</p>
                                    @if($img->description)
                                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit($img->description, 50) }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $img->order }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $img->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $img->is_active ? '✅ Ativo' : ' Inativo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button onclick="editCarousel({{ $img->id }}, '{{ addslashes($img->title) }}', '{{ addslashes($img->description) }}', {{ $img->order }}, {{ $img->is_active }})" 
                                            class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-medium transition"
                                            title="Editar">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <form method="POST" action="{{ route('admin.content.carousel.destroy', $img->id) }}" 
                                          class="inline" onsubmit="return confirm('⚠️ Tem certeza que deseja remover esta imagem?');">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs font-medium transition"
                                                title="Excluir">
                                            <i class="fas fa-trash"></i> Remover
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center text-gray-500">
                <i class="fas fa-images text-6xl text-gray-300 mb-4"></i>
                <p class="text-lg font-medium text-gray-700 mb-2">Nenhuma imagem adicionada ainda</p>
                <p class="text-sm">Use o formulário acima para adicionar a primeira imagem do carrossel.</p>
            </div>
        @endif
    </div>

    <!-- Modal de Edição -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">✏️ Editar Imagem</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf 
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Título</label>
                    <input type="text" name="title" id="editTitle" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Descrição</label>
                    <textarea name="description" id="editDescription" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Ordem</label>
                    <input type="number" name="order" id="editOrder" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nova Imagem (opcional)</label>
                    <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Deixe em branco para manter a imagem atual</p>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                    <select name="is_active" id="editIsActive" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="1">✅ Ativo</option>
                        <option value="0">❌ Inativo</option>
                    </select>
                </div>
                
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-1"></i> Salvar Alterações
                    </button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preview da imagem antes de upload
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Editar imagem
        function editCarousel(id, title, description, order, isActive) {
            document.getElementById('editForm').action = '/admin/conteudo/carrossel/' + id;
            document.getElementById('editTitle').value = title || '';
            document.getElementById('editDescription').value = description || '';
            document.getElementById('editOrder').value = order || 0;
            document.getElementById('editIsActive').value = isActive ? '1' : '0';
            document.getElementById('editModal').classList.remove('hidden');
        }

        // Fechar modal
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Fechar modal ao clicar fora
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</x-layouts.admin>