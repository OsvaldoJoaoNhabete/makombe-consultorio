<x-layouts.admin title="Pagamento #{{ $payment->id }}">

    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('financeiro.payments.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
        @if($payment->status === 'pendente')
            <form method="POST" action="{{ route('financeiro.payments.confirm', $payment->id) }}" class="inline">
                @csrf
                <button type="submit" onclick="return confirm('Confirmar pagamento?');"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 text-green-600 hover:bg-green-100 rounded-lg text-sm font-medium">
                    <i class="fas fa-check"></i> Confirmar
                </button>
            </form>
            <form method="POST" action="{{ route('financeiro.payments.cancel', $payment->id) }}" class="inline">
                @csrf
                <button type="submit" onclick="return confirm('Cancelar pagamento?');"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium">
                    <i class="fas fa-times"></i> Cancelar
                </button>
            </form>
        @endif
    </div>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            @php
                $statusColors = [
                    'confirmado' => 'from-green-600 to-emerald-700',
                    'pendente' => 'from-amber-600 to-orange-700',
                    'cancelado' => 'from-red-600 to-rose-700',
                    'estornado' => 'from-gray-600 to-gray-700',
                ];
                $color = $statusColors[$payment->status] ?? 'from-gray-600 to-gray-700';
            @endphp
            
            <div class="bg-gradient-to-r {{ $color }} px-6 py-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-80">Pagamento #{{ $payment->id }}</p>
                        <h1 class="text-4xl font-bold mt-1">{{ number_format($payment->amount, 2, ',', '.') }} MT</h1>
                        <p class="text-white/80 mt-2">{{ ucfirst($payment->status) }}</p>
                    </div>
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-coins text-4xl"></i>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Paciente</p>
                        <a href="{{ route('patients.show', $payment->patient_id) }}" class="block hover:text-blue-600">
                            <p class="font-bold text-gray-900 text-lg">{{ $payment->patient->full_name }}</p>
                            <p class="text-sm text-gray-600">NID: {{ $payment->patient->nid }}</p>
                        </a>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Método</p>
                        <p class="font-bold text-gray-900 text-lg">
                            @php
                                $methodIcons = [
                                    'mpesa' => '📱 M-Pesa',
                                    'emola' => '📱 e-Mola',
                                    'transferencia' => ' Transferência',
                                    'numerario' => '💵 Numerário',
                                    'cheque' => '📄 Cheque',
                                    'cartao' => '💳 Cartão',
                                    'seguradora' => '🛡️ Seguradora',
                                ];
                            @endphp
                            {{ $methodIcons[$payment->method] ?? ucfirst($payment->method) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Data do Pagamento</p>
                        <p class="font-medium text-gray-900">{{ $payment->paid_at?->format('d/m/Y H:i') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Referência</p>
                        <p class="font-mono text-gray-900">{{ $payment->reference ?? '-' }}</p>
                    </div>
                </div>

                @if($payment->description)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Descrição</p>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-800">
                            {{ $payment->description }}
                        </div>
                    </div>
                @endif

                @if($payment->consultation)
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <p class="text-xs text-blue-800 uppercase font-semibold mb-2">
                            <i class="fas fa-calendar-check mr-1"></i> Consulta Vinculada
                        </p>
                        <p class="text-sm text-gray-800">
                            <strong>{{ $payment->consultation->scheduled_at->format('d/m/Y H:i') }}</strong> - 
                            Dr(a). {{ $payment->consultation->doctor->name ?? '-' }}
                        </p>
                    </div>
                @endif

                <div class="pt-6 border-t">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Informações do Sistema</p>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Registado em:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $payment->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($payment->createdBy)
                            <div>
                                <span class="text-gray-600">Por:</span>
                                <span class="font-medium text-gray-900 ml-2">{{ $payment->createdBy->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>