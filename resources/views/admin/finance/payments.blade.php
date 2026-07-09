<x-layouts.admin title="Pagamentos">

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">💳 Pagamentos</h1>
            <p class="text-gray-600">Lista completa de pagamentos</p>
        </div>
        <a href="{{ route('financeiro.payments.create') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition">
            <i class="fas fa-plus"></i> Novo Pagamento
        </a>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('financeiro.payments.index') }}" class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <p class="text-xs text-gray-500 uppercase font-semibold">Total</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </a>
        <a href="{{ route('financeiro.payments.index', ['status' => 'confirmado']) }}" class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <p class="text-xs text-green-600 uppercase font-semibold">Confirmados</p>
            <p class="text-2xl font-bold text-green-700 mt-1">{{ $stats['confirmados'] }}</p>
        </a>
        <a href="{{ route('financeiro.payments.index', ['status' => 'pendente']) }}" class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <p class="text-xs text-amber-600 uppercase font-semibold">Pendentes</p>
            <p class="text-2xl font-bold text-amber-700 mt-1">{{ $stats['pendentes'] }}</p>
        </a>
        <a href="{{ route('financeiro.payments.index', ['status' => 'cancelado']) }}" class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <p class="text-xs text-red-600 uppercase font-semibold">Cancelados</p>
            <p class="text-2xl font-bold text-red-700 mt-1">{{ $stats['cancelados'] }}</p>
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
        <form method="GET" action="{{ route('financeiro.payments.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <input type="date" name="date_from" value="{{ $dateFrom }}" 
                   class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="De">
            <input type="date" name="date_to" value="{{ $dateTo }}" 
                   class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="Até">
            <select name="status" class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos os Status</option>
                <option value="confirmado" {{ $status === 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                <option value="pendente" {{ $status === 'pendente' ? 'selected' : '' }}>Pendente</option>
                <option value="cancelado" {{ $status === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            </select>
            <select name="method" class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all" {{ $method === 'all' ? 'selected' : '' }}>Todos os Métodos</option>
                <option value="mpesa" {{ $method === 'mpesa' ? 'selected' : '' }}>M-Pesa</option>
                <option value="emola" {{ $method === 'emola' ? 'selected' : '' }}>e-Mola</option>
                <option value="transferencia" {{ $method === 'transferencia' ? 'selected' : '' }}>Transferência</option>
                <option value="numerario" {{ $method === 'numerario' ? 'selected' : '' }}>Numerário</option>
                <option value="cartao" {{ $method === 'cartao' ? 'selected' : '' }}>Cartão</option>
                <option value="seguradora" {{ $method === 'seguradora' ? 'selected' : '' }}>Seguradora</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    <i class="fas fa-filter mr-1"></i> Filtrar
                </button>
                <a href="{{ route('financeiro.payments.index') }}" class="px-3 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        @if($payments->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Paciente</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Valor</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Método</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Referência</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm">
                                    <p class="font-medium text-gray-900">{{ $payment->created_at->format('d/m/Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $payment->created_at->format('H:i') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('patients.show', $payment->patient_id) }}" class="hover:text-blue-600">
                                        <p class="font-medium text-sm">{{ $payment->patient->full_name ?? '-' }}</p>
                                    </a>
                                </td>
                                <td class="px-6 py-4 font-bold text-green-600">
                                    {{ number_format($payment->amount, 2, ',', '.') }} MT
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @php
                                        $methodIcons = [
                                            'mpesa' => '📱 M-Pesa',
                                            'emola' => '📱 e-Mola',
                                            'transferencia' => ' Transferência',
                                            'numerario' => '💵 Numerário',
                                            'cheque' => '📄 Cheque',
                                            'cartao' => '💳 Cartão',
                                            'seguradora' => '️ Seguradora',
                                        ];
                                    @endphp
                                    {{ $methodIcons[$payment->method] ?? ucfirst($payment->method) }}
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-gray-600">
                                    {{ $payment->reference ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClass = match($payment->status) {
                                            'confirmado' => 'bg-green-100 text-green-800',
                                            'pendente' => 'bg-amber-100 text-amber-800',
                                            'cancelado' => 'bg-red-100 text-red-800',
                                            'estornado' => 'bg-gray-100 text-gray-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('financeiro.payments.show', $payment->id) }}" 
                                           class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-xs">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($payment->status === 'pendente')
                                            <form method="POST" action="{{ route('financeiro.payments.confirm', $payment->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Confirmar pagamento?');"
                                                        class="px-3 py-1.5 bg-green-50 text-green-600 hover:bg-green-100 rounded-lg text-xs">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('financeiro.payments.cancel', $payment->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Cancelar pagamento?');"
                                                        class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="px-6 py-4 border-t bg-gray-50">
                    {{ $payments->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <i class="fas fa-coins text-6xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Nenhum pagamento encontrado</h4>
                <p class="text-gray-500 mb-4">
                    @if($status !== 'all' || $method !== 'all' || $dateFrom || $dateTo)
                        Não há pagamentos para os filtros selecionados.
                    @else
                        Comece por registar o primeiro pagamento.
                    @endif
                </p>
                <a href="{{ route('financeiro.payments.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition">
                    <i class="fas fa-plus"></i> Registar Primeiro Pagamento
                </a>
            </div>
        @endif
    </div>

</x-layouts.admin>