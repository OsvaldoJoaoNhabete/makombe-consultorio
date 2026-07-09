<x-layouts.admin title="{{ isset($quote) ? 'Editar Cotação' : 'Nova Cotação' }}">

    <div class="mb-4">
        <a href="{{ route('quotes.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-arrow-left"></i> Voltar para cotações
        </a>
    </div>

    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            
            <div class="mb-6 pb-6 border-b">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    {{ isset($quote) ? '✏️ Editar Cotação' : '📋 Nova Cotação' }}
                </h1>
                <p class="text-gray-600">
                    {{ isset($quote) ? 'Atualize os dados da cotação.' : 'Crie um novo orçamento para o paciente.' }}
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
                  action="{{ isset($quote) ? route('quotes.update', $quote->id) : route('quotes.store') }}" 
                  id="quoteForm"
                  class="space-y-6">
                @csrf
                @if(isset($quote))
                    @method('PUT')
                @endif

                <!-- Paciente e Seguradora -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-blue-600 mr-1"></i> Paciente <span class="text-red-500">*</span>
                        </label>
                        <select name="patient_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecione um paciente...</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}" {{ old('patient_id', $quote->patient_id ?? '') == $p->id ? 'selected' : '' }}>
                                    {{ $p->full_name }} ({{ $p->nid }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-shield-alt text-purple-600 mr-1"></i> Seguradora
                        </label>
                        <select name="insurance_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Nenhuma (particular)</option>
                            @foreach($insurances as $i)
                                <option value="{{ $i->id }}" {{ old('insurance_id', $quote->insurance_id ?? '') == $i->id ? 'selected' : '' }}>
                                    {{ $i->name }} ({{ $i->getCoverageFormatted() }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Validade -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar text-amber-600 mr-1"></i> Validade da Cotação
                    </label>
                    <input type="date" name="valid_until" 
                           value="{{ old('valid_until', isset($quote) && $quote->valid_until ? $quote->valid_until->format('Y-m-d') : now()->addDays(15)->format('Y-m-d')) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-info-circle mr-1"></i> Data até quando esta cotação é válida
                    </p>
                </div>

                <!-- Itens da Cotação -->
                <div class="border-t pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-list text-blue-600"></i> Itens da Cotação
                        </h3>
                        <button type="button" onclick="addItem()" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition text-sm">
                            <i class="fas fa-plus"></i> Adicionar Item
                        </button>
                    </div>

                    <div id="itemsContainer" class="space-y-3">
                        @php
                            $oldItems = old('items', isset($quote) ? $quote->items->map(fn($i) => [
                                'procedure_id' => $i->procedure_id,
                                'description' => $i->description,
                                'quantity' => $i->quantity,
                                'unit_price' => $i->unit_price,
                            ])->toArray() : []);
                            
                            if (empty($oldItems)) {
                                $oldItems = [[]]; // Pelo menos um item vazio
                            }
                        @endphp
                        
                        @foreach($oldItems as $index => $item)
                            @include('admin.quotes._item-row', ['index' => $index, 'item' => $item, 'proceduresByCategory' => $proceduresByCategory])
                        @endforeach
                    </div>
                </div>

                <!-- Desconto -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-percentage text-green-600"></i> Desconto
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Valor do Desconto</label>
                            <input type="number" name="discount" step="0.01" min="0"
                                   value="{{ old('discount', $quote->discount ?? 0) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Desconto</label>
                            <select name="discount_type"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="fixed" {{ old('discount_type', $quote->discount_type ?? 'fixed') === 'fixed' ? 'selected' : '' }}>
                                    💵 Valor Fixo (MT)
                                </option>
                                <option value="percentage" {{ old('discount_type', $quote->discount_type ?? '') === 'percentage' ? 'selected' : '' }}>
                                    📊 Percentagem (%)
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Observações -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-amber-600 mr-1"></i> Observações
                    </label>
                    <textarea name="notes" rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Condições especiais, observações adicionais...">{{ old('notes', $quote->notes ?? '') }}</textarea>
                </div>

                <!-- Botões -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
                    <button type="submit" 
                            class="flex-1 py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> {{ isset($quote) ? 'Atualizar' : 'Criar' }} Cotação
                    </button>
                    <a href="{{ route('quotes.index') }}" 
                       class="flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Template para novos itens (clonado via JS) -->
    <template id="itemTemplate">
        @include('admin.quotes._item-row', ['index' => '__INDEX__', 'item' => [], 'proceduresByCategory' => $proceduresByCategory])
    </template>

    <script>
        let itemCount = {{ count($oldItems) }};

        function addItem() {
            const template = document.getElementById('itemTemplate').innerHTML;
            const newItem = template.replace(/__INDEX__/g, itemCount);
            document.getElementById('itemsContainer').insertAdjacentHTML('beforeend', newItem);
            itemCount++;
            updateRemoveButtons();
        }

        function removeItem(button) {
            const container = document.getElementById('itemsContainer');
            if (container.children.length > 1) {
                button.closest('.item-row').remove();
                updateRemoveButtons();
            } else {
                alert('A cotação deve ter pelo menos um item.');
            }
        }

        function updateRemoveButtons() {
            const container = document.getElementById('itemsContainer');
            const removeButtons = container.querySelectorAll('.remove-item-btn');
            if (container.children.length <= 1) {
                removeButtons.forEach(btn => btn.style.display = 'none');
            } else {
                removeButtons.forEach(btn => btn.style.display = 'inline-flex');
            }
        }

        function selectProcedure(select, index) {
            const option = select.options[select.selectedIndex];
            if (option && option.value) {
                const price = option.dataset.price;
                const description = option.dataset.description || option.text;
                const row = select.closest('.item-row');
                
                if (price) {
                    row.querySelector(`[name="items[${index}][unit_price]"]`).value = price;
                }
                if (description && !row.querySelector(`[name="items[${index}][description]"]`).value) {
                    row.querySelector(`[name="items[${index}][description]"]`).value = description;
                }
            }
        }

        function calculateItemTotal(index) {
            const row = document.querySelector(`[data-item-index="${index}"]`);
            if (!row) return;
            
            const quantity = parseFloat(row.querySelector(`[name="items[${index}][quantity]"]`).value) || 0;
            const unitPrice = parseFloat(row.querySelector(`[name="items[${index}][unit_price]"]`).value) || 0;
            const total = quantity * unitPrice;
            
            row.querySelector('.item-total').textContent = total.toFixed(2).replace('.', ',') + ' MT';
            
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            let total = 0;
            document.querySelectorAll('.item-total').forEach(el => {
                const value = parseFloat(el.textContent.replace(' MT', '').replace(',', '.')) || 0;
                total += value;
            });
            
            const discountInput = document.querySelector('[name="discount"]');
            const discountType = document.querySelector('[name="discount_type"]').value;
            const discount = parseFloat(discountInput.value) || 0;
            
            let finalTotal = total;
            if (discount > 0) {
                if (discountType === 'percentage') {
                    finalTotal = total - (total * discount / 100);
                } else {
                    finalTotal = total - discount;
                }
            }
            
            document.getElementById('grandTotal').textContent = total.toFixed(2).replace('.', ',') + ' MT';
            document.getElementById('finalTotal').textContent = finalTotal.toFixed(2).replace('.', ',') + ' MT';
        }

        // Atualizar total ao mudar desconto
        document.querySelector('[name="discount"]').addEventListener('input', calculateGrandTotal);
        document.querySelector('[name="discount_type"]').addEventListener('change', calculateGrandTotal);

        // Calcular total inicial
        document.addEventListener('DOMContentLoaded', function() {
            calculateGrandTotal();
            updateRemoveButtons();
        });
    </script>

</x-layouts.admin>