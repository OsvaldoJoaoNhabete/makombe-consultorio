<x-layouts.admin title="Financeiro">

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">💰 Financeiro</h1>
            <p class="text-gray-600">Gestão de pagamentos e receitas</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('financeiro.reports') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition">
                <i class="fas fa-chart-bar"></i> Relatórios
            </a>
            <a href="{{ route('financeiro.payments.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition">
                <i class="fas fa-plus"></i> Novo Pagamento
            </a>
        </div>
    </div>

    <!-- Período -->
    <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-sm font-semibold text-gray-700 mr-2">Período:</span>
            <a href="{{ route('financeiro.index', ['period' => 'today']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $period === 'today' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Hoje
            </a>
            <a href="{{ route('financeiro.index', ['period' => 'week']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $period === 'week' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Esta Semana
            </a>
            <a href="{{ route('financeiro.index', ['period' => 'month']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $period === 'month' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Este Mês
            </a>
            <a href="{{ route('financeiro.index', ['period' => 'year']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $period === 'year' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Este Ano
            </a>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-5 rounded-xl shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Receita Total</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($stats['total_receita'], 2, ',', '.') }}</p>
                    <p class="text-xs opacity-75 mt-1">MT</p>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-coins text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-5 rounded-xl shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Pendente</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($stats['total_pendente'], 2, ',', '.') }}</p>
                    <p class="text-xs opacity-75 mt-1">MT</p>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-5 rounded-xl shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Pagamentos</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['total_pagamentos'] }}</p>
                    <p class="text-xs opacity-75 mt-1">confirmados</p>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-5 rounded-xl shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Média</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($stats['media_pagamento'], 2, ',', '.') }}</p>
                    <p class="text-xs opacity-75 mt-1">MT por pagamento</p>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Gráfico de Linha - Receita 7 dias -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-line text-blue-600"></i> Receita - Últimos 7 Dias
            </h3>
            <canvas id="revenueChart" height="200"></canvas>
        </div>

        <!-- Gráfico de Pizza - Por Método -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-purple-600"></i> Pagamentos por Método
            </h3>
            <canvas id="methodChart" height="200"></canvas>
        </div>
    </div>

    <!-- Pagamentos Recentes -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-history text-blue-600"></i> Pagamentos Recentes
            </h3>
            <a href="{{ route('financeiro.payments.index') }}" class="text-sm text-blue-600 hover:underline">Ver todos</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Paciente</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Valor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Método</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
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
                                        'emola' => ' e-Mola',
                                        'transferencia' => '🏦 Transferência',
                                        'numerario' => '💵 Numerário',
                                        'cheque' => '📄 Cheque',
                                        'cartao' => ' Cartão',
                                        'seguradora' => '🛡️ Seguradora',
                                    ];
                                @endphp
                                {{ $methodIcons[$payment->method] ?? ucfirst($payment->method) }}
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-coins text-4xl text-gray-300 mb-3"></i>
                                <p>Nenhum pagamento registado</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts dos Gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de Receita - 7 dias
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($last7Days),
                datasets: [{
                    label: 'Receita (MT)',
                    data: @json($revenue7Days),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
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

        // Gráfico de Métodos
        const methodCtx = document.getElementById('methodChart').getContext('2d');
        const methodData = @json($paymentsByMethod);
        const methodLabels = methodData.map(m => {
            const labels = {
                'mpesa': 'M-Pesa',
                'emola': 'e-Mola',
                'transferencia': 'Transferência',
                'numerario': 'Numerário',
                'cheque': 'Cheque',
                'cartao': 'Cartão',
                'seguradora': 'Seguradora'
            };
            return labels[m.method] || m.method;
        });
        const methodValues = methodData.map(m => m.total);
        const methodColors = ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444', '#ec4899', '#06b6d4'];

        new Chart(methodCtx, {
            type: 'doughnut',
            data: {
                labels: methodLabels,
                datasets: [{
                    data: methodValues,
                    backgroundColor: methodColors,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 15 } }
                }
            }
        });
    </script>

</x-layouts.admin>