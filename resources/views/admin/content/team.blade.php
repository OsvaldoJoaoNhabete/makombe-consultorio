<x-layouts.admin title="Gerir Equipa Médica">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">👨‍⚕️ Equipa Médica</h1>
            <p class="text-gray-600">Gerencie os perfis dos profissionais de saúde.</p>
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
        <h3 class="font-bold text-gray-900 mb-4"> Adicionar Membro da Equipa</h3>
        <form method="POST" action="{{ route('admin.content.team.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nome Completo *</label>
                <input type="text" name="name" required value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Cargo/Especialidade *</label>
                <input type="text" name="position" required value="{{ old('position') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Descrição Breve</label>
                <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Foto (Opcional)</label>
                <input type="file" name="photo" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Ordem</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Facebook (URL)</label>
                <input type="url" name="facebook" value="{{ old('facebook') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">LinkedIn (URL)</label>
                <input type="url" name="linkedin" value="{{ old('linkedin') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="px-6 py-2.5 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700 transition flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> Adicionar Membro
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Membros -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($members as $member)
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="h-48 bg-gray-200 relative">
                    @if($member->photo_path && file_exists(storage_path('app/public/' . $member->photo_path)))
                        <img src="{{ asset('storage/' . $member->photo_path) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <i class="fas fa-user-md text-4xl"></i>
                        </div>
                    @endif
                    <span class="absolute top-2 right-2 px-2 py-1 text-xs font-semibold rounded-full {{ $member->is_active ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                        {{ $member->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
                <div class="p-4">
                    <h4 class="font-bold text-gray-900">{{ $member->name }}</h4>
                    <p class="text-sm text-purple-600 font-semibold mb-2">{{ $member->position }}</p>
                    <p class="text-xs text-gray-600 mb-4 line-clamp-2">{{ $member->description }}</p>
                    <div class="flex justify-between items-center">
                        <div class="flex gap-2">
                            <button onclick="editTeam({{ $member->id }}, '{{ addslashes($member->name) }}', '{{ addslashes($member->position) }}', '{{ addslashes($member->description) }}', {{ $member->order }}, {{ $member->is_active }}, '{{ $member->facebook }}', '{{ $member->linkedin }}', '{{ $member->whatsapp }}')" 
                                    class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-medium transition">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <form method="POST" action="{{ route('admin.content.team.destroy', $member->id) }}" onsubmit="return confirm('⚠️ Remover este membro?');">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs font-medium transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-gray-500">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <p class="text-lg">Nenhum membro adicionado.</p>
            </div>
        @endforelse
    </div>

    <!-- Modal de Edição -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl p-6 max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">✏️ Editar Membro</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PUT')
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nome *</label>
                        <input type="text" name="name" id="editName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Cargo *</label>
                        <input type="text" name="position" id="editPosition" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Descrição</label>
                    <textarea name="description" id="editDescription" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nova Foto (opcional)</label>
                    <input type="file" name="photo" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Facebook</label>
                        <input type="url" name="facebook" id="editFacebook" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">LinkedIn</label>
                        <input type="url" name="linkedin" id="editLinkedin" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">WhatsApp</label>
                        <input type="text" name="whatsapp" id="editWhatsapp" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Ordem</label>
                        <input type="number" name="order" id="editOrder" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                    <select name="is_active" id="editIsActive" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="1">✅ Ativo</option>
                        <option value="0">❌ Inativo</option>
                    </select>
                </div>
                
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700 transition">
                        <i class="fas fa-save mr-1"></i> Salvar
                    </button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editTeam(id, name, position, description, order, isActive, facebook, linkedin, whatsapp) {
            document.getElementById('editForm').action = '/admin/conteudo/equipa/' + id;
            document.getElementById('editName').value = name;
            document.getElementById('editPosition').value = position;
            document.getElementById('editDescription').value = description;
            document.getElementById('editOrder').value = order;
            document.getElementById('editIsActive').value = isActive ? '1' : '0';
            document.getElementById('editFacebook').value = facebook || '';
            document.getElementById('editLinkedin').value = linkedin || '';
            document.getElementById('editWhatsapp').value = whatsapp || '';
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });
    </script>
</x-layouts.admin>