<x-layouts.admin title="Novo Pagamento">

    <div class="mb-4">
        <a href="{{ route('financeiro.payments.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-arrow-left"></i> Voltar para pagamentos
        </a>
    </div>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            
            <div class="mb-6 pb-6 border-b">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">💳 Novo Pagamento</h1>
                <p class="text-gray-600">Registe um novo pagamento recebido.</p>
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

            <form method="POST" action="{{ route('financeiro.payments.store') }}" class="space-y-6">
                @csrf

                <!-- Paciente -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-blue-600 mr-1"></i> Paciente <span class="text-red-500">*</span>
                    </label>
                    <select name="patient_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione um paciente...</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->full_name }} ({{ $p->nid }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Consulta Vinculada (opcional) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-check text-blue-600 mr-1"></i> Consulta Vinculada (opcional)
                    </label>
                    <select name="consultation_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Nenhuma (pagamento avulso)</option>
                        @foreach($consultations as $c)
                            <option value="{{ $c->id }}" {{ old('consultation_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->scheduled_at->format('d/m/Y H:i') }} - {{ $c->patient->full_name }} - {{ number_format($c->total_amount, 2, ',', '.') }} MT
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Valor e Método -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-coins text-green-600 mr-1"></i> Valor (MT) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="amount" step="0.01" min="0.01" required
                               value="{{ old('amount') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-credit-card text-blue-600 mr-1"></i> Método <span class="text-red-500">*</span>
                        </label>
                        <select name="method" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecione...</option>
                            <option value="mpesa" {{ old('method') === 'mpesa' ? 'selected' : '' }}>📱 M-Pesa</option>
                            <option value="emola" {{ old('method') === 'emola' ? 'selected' : '' }}>📱 e-Mola</option>
                            <option value="transferencia" {{ old('method') === 'transferencia' ? 'selected' : '' }}> Transferência Bancária</option>
                            <option value="numerario" {{ old('method') === 'numerario' ? 'selected' : '' }}> Numerário</option>
                            <option value="cheque" {{ old('method') === 'cheque' ? 'selected' : '' }}> Cheque</option>
                            <option value="cartao" {{ old('method') === 'cartao' ? 'selected' : '' }}> Cartão</option>
                            <option value="seguradora" {{ old('method') === 'seguradora' ? 'selected' : '' }}>️ Seguradora</option>
                        </select>
                    </div>
                </div>

                <!-- Referência e Descrição -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-hashtag text-gray-600 mr-1"></i> Referência / Nº Transação
                        </label>
                        <input type="text" name="reference" value="{{ old('reference') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ex: MP230709.1234.A123">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar text-gray-600 mr-1"></i> Data do Pagamento
                        </label>
                        <input type="date" name="paid_at" value="{{ old('paid_at', date('Y-m-d')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-amber-600 mr-1"></i> Descrição (opcional)
                    </label>
                    <textarea name="description" rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Observações sobre o pagamento...">{{ old('description') }}</textarea>
                </div>

                <!-- Botões -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
                    <button type="submit" 
                            class="flex-1 py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> Registar Pagamento
                    </button>
                    <a href="{{ route('financeiro.payments.index') }}" 
                       class="flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>