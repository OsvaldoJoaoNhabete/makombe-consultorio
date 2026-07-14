<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Cotações • Portal do Paciente - Makombe</title>
    
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
                <h2 class="text-xl font-bold text-gray-800">Minhas Cotações</h2>
                <div></div>
            </div>
        </header>

        <main class="p-6">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 flex items-center gap-3">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">📋 Minhas Cotações</h1>
                <p class="text-gray-600">Veja os orçamentos e propostas médicas.</p>
            </div>

            <!-- Lista de Cotações -->
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                @if($quotes->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($quotes as $quote)
                            <div class="p-5 hover:bg-gray-50 transition">
                                <div class="flex flex-col md:flex-row md:items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-14 h-14 rounded-xl bg-purple-100 flex items-center justify-center">
                                            <i class="fas fa-file-invoice-dollar text-purple-600 text-2xl"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between flex-wrap gap-2 mb-2">
                                            <div>
                                                <h3 class="font-bold text-gray-900 text-lg">
                                                    Cotação #{{ str_pad($quote->id, 5, '0', STR_PAD_LEFT) }}
                                                </h3>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    <i class="fas fa-calendar text-gray-400 mr-1"></i>
                                                    {{ $quote->created_at->format('d/m/Y \à\s H:i') }}
                                                </p>
                                                @if($quote->valid_until)
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        Válida até: {{ $quote->valid_until->format('d/m/Y') }}
                                                        @if($quote->isExpired())
                                                            <span class="text-red-600 font-semibold">(Expirada)</span>
                                                        @endif
                                                    </p>
                                                @endif
                                            </div>
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $quote->getStatusBadgeClass() }}">
                                                {{ $quote->getStatusLabel() }}
                                            </span>
                                        </div>

                                        <!-- Itens -->
                                        <div class="mt-3 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                            <p class="text-xs text-gray-600 font-semibold mb-2">
                                                <i class="fas fa-list mr-1"></i> {{ $quote->items->count() }} {{ Str::plural('item', $quote->items->count()) }}
                                            </p>
                                            <div class="space-y-1">
                                                @foreach($quote->items->take(3) as $item)
                                                    <p class="text-xs text-gray-700">
                                                        • {{ $item->description }} ({{ $item->quantity }}x) - {{ $item->getFormattedTotal() }}
                                                    </p>
                                                @endforeach
                                                @if($quote->items->count() > 3)
                                                    <p class="text-xs text-gray-500 italic">
                                                        + {{ $quote->items->count() - 3 }} mais item(s)...
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Totais -->
                                        <div class="mt-3 flex flex-wrap gap-4">
                                            <div>
                                                <p class="text-xs text-gray-500">Subtotal</p>
                                                <p class="text-sm font-semibold text-gray-900">{{ number_format($quote->total_amount, 2, ',', '.') }} MT</p>
                                            </div>
                                            @if($quote->discount > 0)
                                                <div>
                                                    <p class="text-xs text-gray-500">Desconto</p>
                                                    <p class="text-sm font-semibold text-red-600">-{{ $quote->getFormattedDiscount() }}</p>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-xs text-gray-500">Total</p>
                                                <p class="text-lg font-bold text-green-600">{{ $quote->getFormattedTotal() }}</p>
                                            </div>
                                        </div>

                                        <!-- Botão Ver Detalhes -->
                                        <div class="mt-3">
                                            <a href="{{ route('patient.quotes.show', $quote->id) }}" 
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg transition">
                                                <i class="fas fa-eye"></i> Ver Detalhes Completos
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
                            As cotações são criadas pela equipa do consultório após avaliação médica.
                        </p>
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 max-w-md mx-auto">
                            <p class="text-sm text-blue-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Como funciona?</strong><br>
                                Após uma consulta, o médico pode criar uma cotação com os procedimentos necessários. 
                                Você receberá uma notificação quando uma nova cotação estiver disponível.
                            </p>
                        </div>
                    </div>
                @endif
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