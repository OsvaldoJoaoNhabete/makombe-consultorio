<x-layouts.admin title="Cotações">

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">📋 Cotações</h1>
            <p class="text-gray-600">Gestão de orçamentos e propostas médicas</p>
        </div>
        <a href="{{ route('quotes.create') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition">
            <i class="fas fa-plus"></i> Nova Cotação
        </a>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
        <a href="{{ route('quotes.index') }}" class="bg-white p-4 rounded-xl shadow-sm border hover:shadow-md transition">
            <p class="text-xs text-gray-500 uppercase font-semibold">Total</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </a>
        <a href="{{ route('quotes.index', ['status' => 'rascunho']) }}" class="bg-white p-4 rounded-xl shadow-sm border hover:shadow-md transition">
            <p class="text-xs text-gray-600 uppercase font-semibold">Rascunhos</p>
            <p class="text-2xl font-bold text-gray-700 mt-1">{{ $stats['rascunhos'] }}</p>
        </a>
        <a href="{{ route('quotes.index', ['status' => 'enviada']) }}" class="bg-white p-4 rounded-xl shadow-sm border hover:shadow-md transition">
            <p class="text-xs text-blue-600 uppercase font-semibold">Enviadas</p>
            <p class="text-2xl font-bold text-blue-700 mt-1">{{ $stats['enviadas'] }}</p>
        </a>
        <a href="{{ route('quotes.index', ['status' => 'aprovada']) }}" class="bg-white p-4 rounded-xl shadow-sm border hover:shadow-md transition">
            <p class="text-xs text-green-600 uppercase font-semibold">Aprovadas</p>
            <p class="text-2xl font-bold text-green-700 mt-1">{{ $stats['aprovadas'] }}</p>
        </a>
        <a href="{{ route('quotes.index', ['status' => 'recusada']) }}" class="bg-white p-4 rounded-xl shadow-sm border hover:shadow-md transition">
            <p class="text-xs text-red-600 uppercase font-semibold">Recusadas</p>
            <p class="text-2xl font-bold text-red-700 mt-1">{{ $stats['recusadas'] }}</p>
        </a>
        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-xl shadow-lg text-white">
            <p class="text-xs opacity-90 uppercase font-semibold">Valor Aprovado</p>
            <p class="text-xl font-bold mt-1">{{ number_format($stats['valor_total'], 0, ',', '.') }}</p>
            <p class="text-xs opacity-75">MT</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
        <form method="GET" action="{{ route('quotes.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <input type="date" name="date_from" value="{{ $dateFrom }}" 
                   class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="De">
            <input type="date" name="date_to" value="{{ $dateTo }}" 
                   class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="Até">
            <select name="status" class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos os Status</option>
                <option value="rascunho" {{ $status === 'rascunho' ? 'selected' : '' }}>📝 Rascunho</option>
                <option value="enviada" {{ $status === 'enviada' ? 'selected' : '' }}>📤 Enviada</option>
                <option value="aprovada" {{ $status === 'aprovada' ? 'selected' : '' }}>✅ Aprovada</option>
                <option value="recusada" {{ $status === 'recusada' ? 'selected' : '' }}>❌ Recusada</option>
                <option value="paga" {{ $status === 'paga' ? 'selected' : '' }}>💰 Paga</option>
            </select>
            <div class="md:col-span-2 relative">
                <input type="text" name="search" value="{{ $search }}" 
                       placeholder="🔍 Buscar por paciente ou NID..."
                       class="w-full px-4 py-2.5 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </form>
        <div class="flex gap-2 mt-3">
            <button type="submit" form="{{ request()->url() }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm">
                <i class="fas fa-filter mr-1"></i> Filtrar
            </button>
            <a href="{{ route('quotes.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium text-sm">
                <i class="fas fa-redo mr-1"></i> Limpar
            </a>
        </div>
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        @if($quotes->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nº</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Paciente</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Validade</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Itens</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Valor</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($quotes as $quote)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm font-semibold text-gray-900">
                                        #{{ str_pad($quote->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('patients.show', $quote->patient_id) }}" class="hover:text-blue-600">
                                        <p class="font-medium text-sm text-gray-900">{{ $quote->patient->full_name ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $quote->patient->nid ?? '-' }}</p>
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $quote->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($quote->valid_until)
                                        @if($quote->isExpired())
                                            <span class="text-red-600 font-medium">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Expirada
                                            </span>
                                        @else
                                            <span class="text-gray-700">{{ $quote->valid_until->format('d/m/Y') }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                        {{ $quote->items->count() }} {{ Str::plural('item', $quote->items->count()) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-green-600">{{ $quote->getFormattedTotal() }}</p>
                                    @if($quote->discount > 0)
                                        <p class="text-xs text-gray-500">
                                            <s>{{ number_format($quote->total_amount, 2, ',', '.') }} MT</s>
                                            <span class="ml-1 text-amber-600">-{{ $quote->getFormattedDiscount() }}</span>
                                        </p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $quote->getStatusBadgeClass() }}">
                                        {{ $quote->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('quotes.show', $quote->id) }}" 
                                           class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-xs"
                                           title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(in_array($quote->status, ['rascunho', 'enviada']))
                                            <a href="{{ route('quotes.edit', $quote->id) }}" 
                                               class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs"
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($quotes->hasPages())
                <div class="px-6 py-4 border-t bg-gray-50">
                    {{ $quotes->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <i class="fas fa-file-invoice-dollar text-6xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Nenhuma cotação encontrada</h4>
                <p class="text-gray-500 mb-4">
                    @if($status !== 'all' || $search || $dateFrom || $dateTo)
                        Não há cotações para os filtros selecionados.
                    @else
                        Comece por criar a primeira cotação.
                    @endif
                </p>
                <a href="{{ route('quotes.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition">
                    <i class="fas fa-plus"></i> Criar Primeira Cotação
                </a>
            </div>
        @endif
    </div>

</x-layouts.admin>