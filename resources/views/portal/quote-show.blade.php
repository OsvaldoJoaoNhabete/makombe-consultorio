<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotação #{{ str_pad($quote->id, 5, '0', STR_PAD_LEFT) }} • Portal do Paciente - Makombe</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
    body { font-family: 'Lato', sans-serif; }
    
    .sidebar { 
        position: fixed; top: 0; left: 0; height: 100vh; width: 270px; 
        background: linear-gradient(180deg, #4c1d95 0%, #5b21b6 50%, #6d28d9 100%); 
        display: flex; flex-direction: column; z-index: 50; 
        transition: transform 0.3s ease;
    }
    
    .nav-item { 
        display: flex; align-items: center; padding: 0.85rem 1.5rem; 
        color: #ddd6fe; text-decoration: none; transition: all 0.2s ease; 
        font-size: 0.95rem; font-weight: 500; border-left: 4px solid transparent; 
    }
    .nav-item:hover { 
        background-color: rgba(255, 255, 255, 0.1); 
        color: #ffffff; 
        border-left-color: #a78bfa;
    }
    .nav-item.active { 
        background-color: rgba(255, 255, 255, 0.15); 
        color: #ffffff; 
        border-left-color: #ffffff;
        font-weight: 700;
    }
    .nav-item i { width: 24px; text-align: center; margin-right: 12px; font-size: 1.1rem; }
    
    .main-content { margin-left: 270px; min-height: 100vh; background-color: #f8fafc; }
    
    @media (max-width: 768px) { 
        .sidebar { transform: translateX(-100%); } 
        .sidebar.open { transform: translateX(0); } 
        .main-content { margin-left: 0; } 
    }
</style>
</head>
<body class="bg-gray-50">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

    <x-portal-sidebar />

    <div class="main-content">
        <header class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <button class="md:hidden text-gray-600" onclick="toggleSidebar()"><i class="fas fa-bars text-xl"></i></button>
                <h2 class="text-xl font-bold text-gray-800">Detalhes da Cotação</h2>
                <div></div>
            </div>
        </header>

        <main class="p-6">
            <div class="mb-4">
                <a href="{{ route('patient.quotes') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-arrow-left"></i> Voltar para cotações
                </a>
            </div>

            <div class="max-w-4xl mx-auto">
                
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

                    <div class="p-6 space-y-6">
                        
                        <!-- Informações -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Seguradora</p>
                                <p class="font-medium text-gray-900">{{ $quote->insurance?->name ?? 'Particular (sem seguro)' }}</p>
                            </div>
                            @if($quote->valid_until)
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Validade</p>
                                    <p class="text-gray-900">
                                        {{ $quote->valid_until->format('d/m/Y') }}
                                        @if($quote->isExpired())
                                            <span class="text-red-600 font-semibold">(Expirada)</span>
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Itens da Cotação -->
                        <div>
                            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fas fa-list text-purple-600"></i> Itens da Cotação
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
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">
                                    <i class="fas fa-sticky-note text-amber-600 mr-1"></i> Observações
                                </p>
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-gray-800 whitespace-pre-line">
                                    {{ $quote->notes }}
                                </div>
                            </div>
                        @endif

                        <!-- Informações do Sistema -->
                        <div class="pt-6 border-t">
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Informações</p>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Criada em:</span>
                                    <span class="font-medium text-gray-900 ml-2">{{ $quote->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($quote->sent_at)
                                    <div>
                                        <span class="text-gray-600">Enviada:</span>
                                        <span class="font-medium text-gray-900 ml-2">{{ $quote->sent_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                                @if($quote->approved_at)
                                    <div>
                                        <span class="text-gray-600">Aprovada:</span>
                                        <span class="font-medium text-green-600 ml-2">{{ $quote->approved_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Ações -->
                        <div class="pt-6 border-t flex flex-wrap gap-2">
                            <a href="{{ route('patient.quotes') }}" 
                               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition">
                                <i class="fas fa-arrow-left mr-1"></i> Voltar
                            </a>
                            <button onclick="window.print()" 
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                                <i class="fas fa-print mr-1"></i> Imprimir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebar-overlay').classList.toggle('hidden');
        }
    </script>
</body>
</html>