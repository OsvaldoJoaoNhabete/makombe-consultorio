<div class="item-row p-4 bg-gray-50 border border-gray-200 rounded-xl" data-item-index="{{ $index }}">
    <div class="grid grid-cols-12 gap-3 items-end">
        <!-- Procedimento -->
        <div class="col-span-12 md:col-span-4">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Procedimento/Serviço</label>
            <select name="items[{{ $index }}][procedure_id]" 
                    onchange="selectProcedure(this, {{ $index }})"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">-- Personalizado --</option>
                @foreach($proceduresByCategory as $category => $procedures)
                    <optgroup label="{{ ucfirst($category) }}">
                        @foreach($procedures as $proc)
                            <option value="{{ $proc->id }}" 
                                    data-price="{{ $proc->price }}"
                                    data-description="{{ $proc->name }}"
                                    {{ old("items.{$index}.procedure_id", $item['procedure_id'] ?? '') == $proc->id ? 'selected' : '' }}>
                                {{ $proc->name }} - {{ $proc->getFormattedPrice() }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        <!-- Descrição -->
        <div class="col-span-12 md:col-span-4">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Descrição *</label>
            <input type="text" name="items[{{ $index }}][description]" required
                   value="{{ old("items.{$index}.description", $item['description'] ?? '') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                   placeholder="Descrição do item">
        </div>

        <!-- Quantidade -->
        <div class="col-span-4 md:col-span-1">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Qtd</label>
            <input type="number" name="items[{{ $index }}][quantity]" required min="1"
                   value="{{ old("items.{$index}.quantity", $item['quantity'] ?? 1) }}"
                   oninput="calculateItemTotal({{ $index }})"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
        </div>

        <!-- Preço Unitário -->
        <div class="col-span-4 md:col-span-2">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Preço Unit. (MT)</label>
            <input type="number" name="items[{{ $index }}][unit_price]" required min="0" step="0.01"
                   value="{{ old("items.{$index}.unit_price", $item['unit_price'] ?? '') }}"
                   oninput="calculateItemTotal({{ $index }})"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                   placeholder="0.00">
        </div>

        <!-- Total -->
        <div class="col-span-3 md:col-span-1">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Total</label>
            <div class="px-3 py-2 bg-green-50 border border-green-200 rounded-lg text-sm font-bold text-green-700 item-total">
                @php
                    $qty = old("items.{$index}.quantity", $item['quantity'] ?? 1);
                    $price = old("items.{$index}.unit_price", $item['unit_price'] ?? 0);
                    $total = $qty * $price;
                @endphp
                {{ number_format($total, 2, ',', '.') }} MT
            </div>
        </div>

        <!-- Remover -->
        <div class="col-span-1">
            <button type="button" onclick="removeItem(this)" 
                    class="remove-item-btn inline-flex items-center justify-center w-full h-10 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition"
                    title="Remover item">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
</div>