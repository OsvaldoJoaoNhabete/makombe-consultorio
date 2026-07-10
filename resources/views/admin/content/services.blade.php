<x-layouts.admin title="Gerir Serviços">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">🩺 Serviços Médicos</h1>
            <p class="text-gray-600">Adicione os serviços oferecidos pelo consultório.</p>
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

    <!-- Formulário de Adição -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-4">➕ Adicionar Serviço</h3>
        <form method="POST" action="{{ route('admin.content.service.store') }}" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Título *</label>
                    <input type="text" name="title" required value="{{ old('title') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Ordem</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Selecione o Ícone *</label>
                <div class="grid grid-cols-5 md:grid-cols-10 gap-2 mb-2" id="iconSelector">
                    @php
                        $icons = [
                            'fa-baby' => ['name' => 'Pediatria', 'color' => 'bg-pink-100 text-pink-600'],
                            'fa-allergies' => ['name' => 'Dermatologia', 'color' => 'bg-purple-100 text-purple-600'],
                            'fa-comments' => ['name' => 'Terapia Fala', 'color' => 'bg-blue-100 text-blue-600'],
                            'fa-hands' => ['name' => 'Terapia Ocup.', 'color' => 'bg-orange-100 text-orange-600'],
                            'fa-stethoscope' => ['name' => 'Clínica Geral', 'color' => 'bg-emerald-100 text-emerald-600'],
                            'fa-user-md' => ['name' => 'Med. Interna', 'color' => 'bg-indigo-100 text-indigo-600'],
                            'fa-brain' => ['name' => 'Psicologia', 'color' => 'bg-violet-100 text-violet-600'],
                            'fa-apple-alt' => ['name' => 'Nutrição', 'color' => 'bg-green-100 text-green-600'],
                            'fa-female' => ['name' => 'Ginecologia', 'color' => 'bg-rose-100 text-rose-600'],
                            'fa-heartbeat' => ['name' => 'Cardiologia', 'color' => 'bg-red-100 text-red-600'],
                        ];
                    @endphp
                    @foreach($icons as $iconClass => $iconData)
                        <label class="cursor-pointer">
                            <input type="radio" name="icon" value="{{ $iconClass }}" class="hidden peer" {{ old('icon') === $iconClass ? 'checked' : '' }}>
                            <div class="peer-checked:ring-2 peer-checked:ring-emerald-500 peer-checked:scale-110 {{ $iconData['color'] }} rounded-lg p-3 flex flex-col items-center gap-1 transition hover:shadow-md">
                                <i class="fas {{ $iconClass }} text-xl"></i>
                                <span class="text-[10px] font-semibold text-center leading-tight">{{ $iconData['name'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500">Clique num ícone para selecionar</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Descrição *</label>
                <textarea name="description" required rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 transition flex items-center gap-2">
                <i class="fas fa-plus-circle"></i> Adicionar Serviço
            </button>
        </form>
    </div>

    <!-- Lista de Serviços -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ícone</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Título</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Descrição</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ordem</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($services as $service)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="w-12 h-12 gradient-brand rounded-lg flex items-center justify-center">
                                <i class="fas {{ $service->icon }} text-white text-xl"></i>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $service->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($service->description, 60) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $service->order }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button onclick="editService({{ $service->id }}, '{{ addslashes($service->title) }}', '{{ addslashes($service->description) }}', '{{ $service->icon }}', {{ $service->order }}, {{ $service->is_active }})" 
                                    class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-medium transition">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <form method="POST" action="{{ route('admin.content.service.destroy', $service->id) }}" class="inline" onsubmit="return confirm('️ Remover este serviço?');">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs font-medium transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">Nenhum serviço cadastrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal de Edição -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl p-6 max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">️ Editar Serviço</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            
            <form id="editForm" method="POST" class="space-y-4">
                @csrf @method('PUT')
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Título *</label>
                    <input type="text" name="title" id="editTitle" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ícone</label>
                    <div class="grid grid-cols-5 gap-2" id="editIconSelector">
                        @foreach($icons as $iconClass => $iconData)
                            <label class="cursor-pointer">
                                <input type="radio" name="icon" value="{{ $iconClass }}" class="hidden peer">
                                <div class="peer-checked:ring-2 peer-checked:ring-emerald-500 {{ $iconData['color'] }} rounded-lg p-3 flex flex-col items-center gap-1 transition">
                                    <i class="fas {{ $iconClass }} text-xl"></i>
                                    <span class="text-[10px] font-semibold text-center">{{ $iconData['name'] }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Descrição *</label>
                    <textarea name="description" id="editDescription" required rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Ordem</label>
                        <input type="number" name="order" id="editOrder" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                        <select name="is_active" id="editIsActive" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="1">✅ Ativo</option>
                            <option value="0">❌ Inativo</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 transition">
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
        function editService(id, title, description, icon, order, isActive) {
            document.getElementById('editForm').action = '/admin/conteudo/servicos/' + id;
            document.getElementById('editTitle').value = title;
            document.getElementById('editDescription').value = description;
            document.getElementById('editOrder').value = order;
            document.getElementById('editIsActive').value = isActive ? '1' : '0';
            
            // Selecionar o ícone correto
            const radio = document.querySelector(`#editIconSelector input[value="${icon}"]`);
            if (radio) radio.checked = true;
            
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