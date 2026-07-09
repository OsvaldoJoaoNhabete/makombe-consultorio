<x-layouts.admin title="Cotação #{{ str_pad($quote->id, 5, '0', STR_PAD_LEFT) }}">

    <!-- Ações -->
    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('quotes.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
        <span class="text-gray-300">|</span>
        
        @if(in_array($quote->status, ['rascunho', 'enviada']))
            <a href="{{ route('quotes.edit', $quote->id) }}" 
               class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium">
                <i class="fas fa-edit"></i> Editar
            </a>
        @endif

        @if($quote->status === 'rascunho')
            <form method="POST" action="{{ route('quotes.send', $quote->id) }}" class="inline">
                @csrf
                <button type="submit" onclick="return confirm('Enviar esta cotação ao paciente?');"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-sm font-medium">
                    <i class="fas fa-paper-plane"></i> Enviar
                </button>
            </form>
        @endif

        @if(in_array($quote->status, ['rascunho', 'enviada']))
            <form method="POST" action="{{ route('quotes.approve', $quote->id) }}" class="inline">
                @csrf
                <button type="submit" onclick="return confirm('Aprovar esta cotação?');"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 text-green-600 hover:bg-green-100 rounded-lg text-sm font-medium">
                    <i class="fas fa-check"></i> Aprovar
                </button>
            </form>
            <form method="POST" action="{{ route('quotes.reject', $quote->id) }}" class="inline">
                @csrf
                <button type="submit" onclick="return confirm('Recusar esta cotação?');"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium">
                    <i class="fas fa-times"></i> Recusar
                </button>
            </form>
        @endif

        @if($quote->status === 'aprovada')
            <form method="POST" action="{{ route('quotes.markAsPaid', $quote->id) }}" class="inline">
                @csrf
                <button type="submit" onclick="return confirm('Marcar como paga?');"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg text-sm font-medium">
                    <i class="fas fa-money-bill"></i> Marcar como Paga
                </button>
            </form>
            <form method="POST" action="{{ route('quotes.convert', $quote->id) }}" class="inline">
                @csrf
                <button type="submit" onclick="return confirm('Converter em consulta?');"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg text-sm font-medium">
                    <i class="fas fa-calendar-plus"></i> Converter em Consulta
                </button>
            </form>
        @endif

        @if($quote->status === 'rascunho')
            <form method="POST" action="{{ route('quotes.destroy', $quote->id) }}" 
                  onsubmit="return confirm('Excluir esta cotação?');"
                  class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium">
                    <i class="fas fa-trash"></i> Excluir
                </button>
            </form>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Coluna Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card da Cotação -->
            @php
                $statusGradient = match($quote->status) {
                    'rascunho' => 'from-gray-600 to-gray-700',
                    'enviada' => 'from-blue-600 to-indigo-700',
                    'aprovada' => 'from-green-600 to-emerald-700',
                    'recusada' => 'from-red-600 to-rose-700',
                    'paga' => 'from-purple-600 to-pink-700',
                    'expirada' => 'from-amber-600 to-orange-700',
                    default => 'from-gray-600 to-gray-700',
                };
            @endphp

            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="bg-gradient-to-r {{ $statusGradient }} px-6 py-8 text-white">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div>
                            <p class="text-sm opacity-80">Cotação</p>
                            <h1 class="text-3xl font-bold mt-1">#{{ str_pad($quote->id, 5, '0', STR_PAD_LEFT) }}</h1>
                            <p class="text-white/80 mt-2 text-sm">
                                <i class="fas fa-calendar mr-1"></i> 
                                Criada em {{ $quote->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-semibold">
                                {{ $quote->getStatusLabel() }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Info do Paciente -->
                <div class="p-6 border-b">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Paciente</p>
                            <a href="{{ route('patients.show', $quote->patient_id) }}" class="hover:text-blue-600">
                                <p class="font-bold text-gray-900 text-lg">{{ $quote->patient->full_name }}</p>
                                <p class="text-sm text-gray-600">NID: {{ $quote->patient->nid }}</p>
                                <p class="text-sm text-gray-600">📞 +258 {{ $quote->patient->phone }}</p>
                            </a>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Seguradora</p>
                            <p class="font-medium text-gray-900">{{ $quote->insurance?->name ?? 'Particular' }}</p>
                            @if($quote->valid_until)
                                <p class="text-sm text-gray-600 mt-2">
                                    <i class="fas fa-clock mr-1"></i> 
                                    Válida até: <strong>{{ $quote->valid_until->format('d/m/Y') }}</strong>
                                    @if($quote->isExpired())
                                        <span class="text-red-600 font-semibold">(Expirada)</span>
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Itens da Cotação -->
                <div class="p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-list text-blue-600"></i> Itens da Cotação
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Descrição</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Qtd</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Preço Unit.</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($quote->items as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <p class="font-medium text-sm text-gray-900">{{ $item->description }}</p>
                                            @if($item->procedure)
                                                <p class="text-xs text-gray-500">{{ $item->procedure->getCategoryLabel() }}</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center text-sm">{{ $item->quantity }}</td>
                                        <td class="px-4 py-3 text-right text-sm">{{ number_format($item->unit_price, 2, ',', '.') }} MT</td>
                                        <td class="px-4 py-3 text-right font-bold text-gray-900">{{ $item->getFormattedTotal() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Totais -->
                    <div class="mt-6 pt-6 border-t">
                        <div class="space-y-2 max-w-sm ml-auto">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-medium text-gray-900">{{ number_format($quote->total_amount, 2, ',', '.') }} MT</span>
                            </div>
                            @if($quote->discount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Desconto ({{ $quote->getFormattedDiscount() }}):</span>
                                    <span class="font-medium text-red-600">- {{ number_format($quote->total_amount - $quote->final_amount, 2, ',', '.') }} MT</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-lg pt-2 border-t">
                                <span class="font-bold text-gray-900">Total:</span>
                                <span class="font-bold text-green-600 text-2xl">{{ $quote->getFormattedTotal() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($quote->notes)
                    <div class="p-6 border-t bg-amber-50">
                        <p class="text-xs text-amber-800 uppercase font-semibold mb-2">
                            <i class="fas fa-sticky-note mr-1"></i> Observações
                        </p>
                        <p class="text-sm text-gray-800 whitespace-pre-line">{{ $quote->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Coluna Lateral -->
        <div class="space-y-6">
            
            <!-- Informações do Sistema -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i> Informações
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Criada em:</span>
                        <span class="font-medium text-gray-900">{{ $quote->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($quote->sent_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Enviada:</span>
                            <span class="font-medium text-gray-900">{{ $quote->sent_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                    @if($quote->approved_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Aprovada:</span>
                            <span class="font-medium text-green-600">{{ $quote->approved_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                    @if($quote->rejected_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Recusada:</span>
                            <span class="font-medium text-red-600">{{ $quote->rejected_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                    @if($quote->createdBy)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Criada por:</span>
                            <span class="font-medium text-gray-900">{{ $quote->createdBy->name }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-bolt text-yellow-500"></i> Ações Rápidas
                </h3>
                <div class="space-y-2">
                    @if($quote->status === 'rascunho')
                        <form method="POST" action="{{ route('quotes.send', $quote->id) }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center gap-3 p-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition text-left">
                                <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white">
                                    <i class="fas fa-paper-plane"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">Enviar ao Paciente</p>
                                    <p class="text-xs text-gray-500">Notificar sobre a cotação</p>
                                </div>
                            </button>
                        </form>
                    @endif

                    @if($quote->status === 'aprovada')
                        <form method="POST" action="{{ route('quotes.convert', $quote->id) }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center gap-3 p-3 bg-amber-50 hover:bg-amber-100 rounded-lg transition text-left">
                                <div class="w-10 h-10 bg-amber-600 rounded-lg flex items-center justify-center text-white">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">Converter em Consulta</p>
                                    <p class="text-xs text-gray-500">Agendar automaticamente</p>
                                </div>
                            </button>
                        </form>
                    @endif

                    <button onclick="window.print()" 
                            class="w-full flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition text-left">
                        <div class="w-10 h-10 bg-gray-600 rounded-lg flex items-center justify-center text-white">
                            <i class="fas fa-print"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">Imprimir Cotação</p>
                            <p class="text-xs text-gray-500">Gerar versão em papel</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>