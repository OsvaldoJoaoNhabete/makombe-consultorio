<x-layouts.admin title="Relatórios Financeiros">

    <div class="mb-4">
        <a href="{{ route('financeiro.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-arrow-left"></i> Voltar ao Financeiro
        </a>
    </div>

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">📊 Relatórios Financeiros</h1>
        <p class="text-gray-600">Análise detalhada das receitas</p>
    </div>

    <!-- Filtro de Período -->
    <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
        <form method="GET" action="{{ route('financeiro.reports') }}" class="flex flex-col md:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">De</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" 
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Até</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" 
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                <i class="fas fa-filter mr-1"></i> Gerar Relatório
            </button>
        </form>
    </div>

    <!-- Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-6 rounded-xl shadow-lg text-white">
            <p class="text-sm opacity-90">Receita Total</p>
            <p class="text-4xl font-bold mt-2">{{ number_format($totalReceita, 2, ',', '.') }}</p>
            <p class="text-xs opacity-75 mt-1">MT no período</p>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-6 rounded-xl shadow-lg text-white">
            <p class="text-sm opacity-90">Total de Pagamentos</p>
            <p class="text-4xl font-bold mt-2">{{ $totalPagamentos }}</p>
            <p class="text-xs opacity-75 mt-1">pagamentos confirmados</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-6 rounded-xl shadow-lg text-white">
            <p class="text-sm opacity-90">Média por Pagamento</p>
            <p class="text-4xl font-bold mt-2">{{ number_format($mediaPagamento, 2, ',', '.') }}</p>
            <p class="text-xs opacity-75 mt-1">MT</p>
        </div>
    </div>

    <!-- Gráfico por Dia -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-line text-blue-600"></i> Receita por Dia
        </h3>
        <canvas id="dailyChart" height="100"></canvas>
    </div>

    <!-- Por Método -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-purple-600"></i> Por Método de Pagamento
            </h3>
            <canvas id="methodChart" height="200"></canvas>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-list text-blue-600"></i> Detalhe por Método
            </h3>
            <div class="space-y-3">
                @php
                    $methodLabels = [
                        'mpesa' => '📱 M-Pesa',
                        'emola' => '📱 e-Mola',
                        'transferencia' => '🏦 Transferência',
                        'numerario' => '💵 Numerário',
                        'cheque' => '📄 Cheque',
                        'cartao' => '💳 Cartão',
                        'seguradora' => '🛡️ Seguradora',
                    ];
                    $methodColors = ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444', '#ec4899', '#06b6d4'];
                @endphp
                @forelse($byMethod as $method => $data)
                    @php
                        $percentage = $totalReceita > 0 ? ($data['total'] / $totalReceita) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $methodLabels[$method] ?? ucfirst($method) }}</span>
                            <span class="text-sm font-bold text-gray-900">{{ number_format($data['total'], 2, ',', '.') }} MT ({{ number_format($percentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full" style="width: {{ $percentage }}%; background-color: {{ $methodColors[loop->index % count($methodColors)] }}"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $data['count'] }} pagamento(s)</p>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">Sem dados no período</p>
                @endforelse
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico por Dia
        const dailyCtx = document.getElementById('dailyChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: @json(array_keys($byDay->toArray())),
                datasets: [{
                    label: 'Receita (MT)',
                    data: @json($byDay->pluck('total')->values()->toArray()),
                    backgroundColor: '#10b981',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => v + ' MT' } }
                }
            }
        });

        // Gráfico por Método
        const methodCtx = document.getElementById('methodChart').getContext('2d');
        new Chart(methodCtx, {
            type: 'doughnut',
            data: {
                labels: @json(array_map(fn($m) => $methodLabels[$m] ?? ucfirst($m), array_keys($byMethod->toArray()))),
                datasets: [{
                    data: @json($byMethod->pluck('total')->values()->toArray()),
                    backgroundColor: @json($methodColors),
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    </script>

</x-layouts.admin>