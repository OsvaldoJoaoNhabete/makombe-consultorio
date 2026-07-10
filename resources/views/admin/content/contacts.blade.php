<x-layouts.admin title="Gerir Contactos">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">📞 Informações de Contacto</h1>
            <p class="text-gray-600">Endereços, telefones, emails e horários.</p>
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
        <h3 class="font-bold text-gray-900 mb-4">➕ Adicionar Contacto</h3>
        <form method="POST" action="{{ route('admin.content.contact.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tipo *</label>
                <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                    <option value="address">📍 Endereço</option>
                    <option value="phone">📞 Telefone</option>
                    <option value="email">📧 Email</option>
                    <option value="hours">🕐 Horário</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Etiqueta (Opcional)</label>
                <input type="text" name="label" value="{{ old('label') }}" placeholder="Ex: Principal" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Valor/Texto *</label>
                <input type="text" name="value" required value="{{ old('value') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div class="md:col-span-4">
                <button type="submit" class="px-6 py-2.5 bg-amber-600 text-white font-bold rounded-lg hover:bg-amber-700 transition flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> Adicionar Contacto
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Contactos -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Etiqueta</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Valor</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($contacts as $contact)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            @php
                                $typeIcons = ['address' => '📍', 'phone' => '📞', 'email' => '📧', 'hours' => '🕐'];
                                $typeColors = ['address' => 'bg-blue-100 text-blue-800', 'phone' => 'bg-green-100 text-green-800', 'email' => 'bg-purple-100 text-purple-800', 'hours' => 'bg-amber-100 text-amber-800'];
                            @endphp
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $typeColors[$contact->type] ?? 'bg-gray-100' }}">
                                {{ $typeIcons[$contact->type] ?? '' }} {{ strtoupper($contact->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $contact->label ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $contact->value }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $contact->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $contact->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button onclick="editContact({{ $contact->id }}, '{{ $contact->type }}', '{{ addslashes($contact->label) }}', '{{ addslashes($contact->value) }}', {{ $contact->order }}, {{ $contact->is_active }})" 
                                    class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-medium transition">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <form method="POST" action="{{ route('admin.content.contact.destroy', $contact->id) }}" class="inline" onsubmit="return confirm('⚠️ Remover?');">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs font-medium transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">Nenhum contacto cadastrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal de Edição -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">✏️ Editar Contacto</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            
            <form id="editForm" method="POST" class="space-y-4">
                @csrf @method('PUT')
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tipo *</label>
                    <select name="type" id="editType" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="address">📍 Endereço</option>
                        <option value="phone">📞 Telefone</option>
                        <option value="email">📧 Email</option>
                        <option value="hours">🕐 Horário</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Etiqueta</label>
                    <input type="text" name="label" id="editLabel" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Valor *</label>
                    <input type="text" name="value" id="editValue" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
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
                    <button type="submit" class="flex-1 px-4 py-2 bg-amber-600 text-white font-bold rounded-lg hover:bg-amber-700 transition">
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
        function editContact(id, type, label, value, order, isActive) {
            document.getElementById('editForm').action = '/admin/conteudo/contactos/' + id;
            document.getElementById('editType').value = type;
            document.getElementById('editLabel').value = label || '';
            document.getElementById('editValue').value = value;
            document.getElementById('editOrder').value = order;
            document.getElementById('editIsActive').value = isActive ? '1' : '0';
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