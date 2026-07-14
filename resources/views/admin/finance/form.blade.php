<x-layouts.admin title="Novo Pagamento">

    <div class="mb-4">
        <a href="{{ route('financeiro.payments.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-arrow-left"></i> Voltar para pagamentos
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border p-6 lg:p-8">
            
            <div class="mb-6 pb-6 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">💳 Registar Novo Pagamento</h1>
                <p class="text-gray-600">Preencha os dados abaixo. Se vincular a uma consulta, o valor será preenchido automaticamente.</p>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
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
                    <select name="patient_id" id="patientSelect" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">Selecione um paciente...</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->full_name }} ({{ $p->nid }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Vincular à Consulta (NOVO) -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                    <label class="block text-sm font-semibold text-blue-900 mb-2">
                        <i class="fas fa-link text-blue-600 mr-1"></i> Vincular à Consulta (Opcional)
                    </label>
                    <select name="consultation_id" id="consultationSelect"
                            class="w-full px-4 py-3 border border-blue-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">-- Pagamento Avulso (Não vincular a nenhuma consulta) --</option>
                        @forelse($pendingConsultations as $consult)
                            @php
                                // Calcula o valor pendente (prioriza patient_amount se existir, senão total_amount)
                                $dueAmount = ($consult->patient_amount > 0) ? $consult->patient_amount : $consult->total_amount;
                            @endphp
                            <option value="{{ $consult->id }}" 
                                    data-amount="{{ $dueAmount }}"
                                    data-patient="{{ $consult->patient_id }}">
                                📅 {{ $consult->scheduled_at->format('d/m/Y H:i') }} | 👨‍⚕️ {{ $consult->doctor->name ?? 'Médico' }} | 👤 {{ $consult->patient->full_name }} | 💰 Pendente: {{ number_format($dueAmount, 2, ',', '.') }} MT
                            </option>
                        @empty
                            <option disabled>Nenhuma consulta pendente de pagamento no sistema.</option>
                        @endforelse
                    </select>
                    <p class="text-xs text-blue-700 mt-2">
                        <i class="fas fa-info-circle mr-1"></i> Ao selecionar uma consulta, o campo "Valor" será preenchido automaticamente com o valor pendente.
                    </p>
                </div>

                <!-- Valor e Método -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-coins text-green-600 mr-1"></i> Valor (MT) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="amount" id="amountInput" step="0.01" min="0.01" required
                               value="{{ old('amount') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 font-bold text-lg"
                               placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-credit-card text-purple-600 mr-1"></i> Método de Pagamento <span class="text-red-500">*</span>
                        </label>
                        <select name="method" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">Selecione o método...</option>
                            <option value="mpesa" {{ old('method') === 'mpesa' ? 'selected' : '' }}>📱 M-Pesa</option>
                            <option value="emola" {{ old('method') === 'emola' ? 'selected' : '' }}>📱 e-Mola</option>
                            <option value="transferencia" {{ old('method') === 'transferencia' ? 'selected' : '' }}>🏦 Transferência Bancária</option>
                            <option value="numerario" {{ old('method') === 'numerario' ? 'selected' : '' }}>💵 Numerário</option>
                            <option value="cheque" {{ old('method') === 'cheque' ? 'selected' : '' }}>📄 Cheque</option>
                            <option value="cartao" {{ old('method') === 'cartao' ? 'selected' : '' }}>💳 Cartão</option>
                            <option value="seguradora" {{ old('method') === 'seguradora' ? 'selected' : '' }}>🛡️ Seguradora</option>
                        </select>
                    </div>
                </div>

                <!-- Referência e Data -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                <!-- Descrição -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-amber-600 mr-1"></i> Descrição / Observações
                    </label>
                    <textarea name="description" rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Ex: Pagamento referente à consulta de cardiologia...">{{ old('description') }}</textarea>
                </div>

                <!-- Botões -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="flex-1 py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition flex items-center justify-center gap-2 shadow-md">
                        <i class="fas fa-check-circle"></i> Confirmar e Registar Pagamento
                    </button>
                    <a href="{{ route('financeiro.payments.index') }}" 
                       class="flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Script para Preenchimento Automático -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const consultationSelect = document.getElementById('consultationSelect');
            const amountInput = document.getElementById('amountInput');
            const patientSelect = document.getElementById('patientSelect');

            consultationSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (selectedOption.value) {
                    const amount = selectedOption.getAttribute('data-amount');
                    const patientId = selectedOption.getAttribute('data-patient');

                    // 1. Preencher o valor automaticamente
                    if (amount && amount > 0) {
                        amountInput.value = amount;
                        // Efeito visual para mostrar que foi preenchido
                        amountInput.classList.add('bg-green-50', 'border-green-300');
                        setTimeout(() => {
                            amountInput.classList.remove('bg-green-50', 'border-green-300');
                        }, 1500);
                    }

                    // 2. Preencher o paciente automaticamente (se ainda não estiver selecionado)
                    if (patientId && (!patientSelect.value || patientSelect.value === "")) {
                        patientSelect.value = patientId;
                    }
                } else {
                    // Se desmarcar, limpar o valor (opcional, pode remover se quiser manter)
                    // amountInput.value = '';
                }
            });
        });
    </script>

</x-layouts.admin>