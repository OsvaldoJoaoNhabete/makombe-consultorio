<x-layouts.admin title="Relatórios Gerenciais">

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">📊 Relatórios Gerenciais</h1>
            <p class="text-gray-600">Análise detalhada do desempenho do consultório</p>
        </div>
        
        <!-- Seletor de Período -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('reports.index', ['period' => 'today']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $period === 'today' ? 'bg-blue-600 text-white' : 'bg-white border text-gray-700 hover:bg-gray-50' }}">
                Hoje
            </a>
            <a href="{{ route('reports.index', ['period' => 'week']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $period === 'week' ? 'bg-blue-600 text-white' : 'bg-white border text-gray-700 hover:bg-gray-50' }}">
                Semana
            </a>
            <a href="{{ route('reports.index', ['period' => 'month']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $period === 'month' ? 'bg-blue-600 text-white' : 'bg-white border text-gray-700 hover:bg-gray-50' }}">
                Mês
            </a>
            <a href="{{ route('reports.index', ['period' => 'year']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $period === 'year' ? 'bg-blue-600 text-white' : 'bg-white border text-gray-700 hover:bg-gray-50' }}">
                Ano
            </a>
        </div>
    </div>

    <!-- Cards Principais -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-6 rounded-xl shadow-lg text-white relative overflow-hidden">
            <i class="fas fa-users text-5xl opacity-20 absolute top-2 right-2"></i>
            <p class="text-sm opacity-90">Total de Pacientes</p>
            <p class="text-4xl font-bold mt-2">{{ number_format($totalPatients) }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-6 rounded-xl shadow-lg text-white relative overflow-hidden">
            <i class="fas fa-calendar-check text-5xl opacity-20 absolute top-2 right-2"></i>
            <p class="text-sm opacity-90">Total de Consultas</p>
            <p class="text-4xl font-bold mt-2">{{ number_format($totalConsultations) }}</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-6 rounded-xl shadow-lg text-white relative overflow-hidden">
            <i class="fas fa-coins text-5xl opacity-20 absolute top-2 right-2"></i>
            <p class="text-sm opacity-90">Receita Total</p>
            <p class="text-3xl font-bold mt-2">{{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-xs opacity-75">MT</p>
        </div>
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-6 rounded-xl shadow-lg text-white relative overflow-hidden">
            <i class="fas fa-chart-line text-5xl opacity-20 absolute top-2 right-2"></i>
            <p class="text-sm opacity-90">Receita do Mês</p>
            <p class="text-3xl font-bold mt-2">{{ number_format($monthRevenue, 0, ',', '.') }}</p>
            <p class="text-xs opacity-75">MT</p>
        </div>
    </div>

    <!-- Gráficos Principais -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Receita Mensal -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-line text-blue-600"></i> Receita Mensal (últimos 6 meses)
            </h3>
            <canvas id="revenueChart" height="200"></canvas>
        </div>

        <!-- Consultas por Tipo -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-purple-600"></i> Consultas por Tipo
            </h3>
            <canvas id="typeChart" height="200"></canvas>
        </div>
    </div>

    <!-- Segunda Linha de Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Receita Diária -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-bar text-green-600"></i> Receita dos Últimos 7 Dias
            </h3>
            <canvas id="dailyChart" height="200"></canvas>
        </div>

        <!-- Pacientes Novos -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-user-plus text-amber-600"></i> Pacientes Novos por Mês
            </h3>
            <canvas id="patientsChart" height="200"></canvas>
        </div>
    </div>

    <!-- Consultas por Dia da Semana -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-calendar-week text-indigo-600"></i> Consultas por Dia da Semana
        </h3>
        <canvas id="weekdayChart" height="100"></canvas>
    </div>

    <!-- Top Médicos e Seguradoras -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Médicos -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-user-md text-blue-600"></i> Top 5 Médicos
            </h3>
            <div class="space-y-3">
                @forelse($topDoctors as $index => $item)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 truncate">{{ $item->doctor->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $item->count }} consultas realizadas</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                {{ $item->count }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-user-md text-4xl text-gray-300 mb-2"></i>
                        <p>Sem dados de consultas</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Top Seguradoras -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-shield-alt text-purple-600"></i> Top 5 Seguradoras
            </h3>
            <div class="space-y-3">
                @forelse($topInsurances as $index => $item)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 truncate">{{ $item->insurance->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $item->count }} consultas cobertas</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">
                                {{ $item->count }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-shield-alt text-4xl text-gray-300 mb-2"></i>
                        <p>Sem dados de seguradoras</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Status das Consultas -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-tasks text-blue-600"></i> Distribuição por Status
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            @php
                $statusLabels = [
                    'agendada' => ['label' => 'Agendadas', 'color' => 'blue', 'icon' => 'calendar'],
                    'confirmada' => ['label' => 'Confirmadas', 'color' => 'indigo', 'icon' => 'check-double'],
                    'em_andamento' => ['label' => 'Em Andamento', 'color' => 'amber', 'icon' => 'spinner'],
                    'concluida' => ['label' => 'Concluídas', 'color' => 'green', 'icon' => 'check-circle'],
                    'cancelada' => ['label' => 'Canceladas', 'color' => 'red', 'icon' => 'times-circle'],
                    'faltou' => ['label' => 'Faltou', 'color' => 'gray', 'icon' => 'user-slash'],
                ];
            @endphp
            @foreach($statusLabels as $status => $info)
                <div class="p-4 bg-{{ $info['color'] }}-50 border border-{{ $info['color'] }}-200 rounded-xl text-center">
                    <i class="fas fa-{{ $info['icon'] }} text-{{ $info['color'] }}-600 text-2xl mb-2"></i>
                    <p class="text-xs text-{{ $info['color'] }}-700 uppercase font-semibold">{{ $info['label'] }}</p>
                    <p class="text-2xl font-bold text-{{ $info['color'] }}-900 mt-1">
                        {{ $consultationsByStatus[$status] ?? 0 }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Scripts dos Gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Receita Mensal
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: @json($monthlyLabels),
                datasets: [{
                    label: 'Receita (MT)',
                    data: @json($monthlyRevenue),
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
                scales: { y: { beginAtZero: true, ticks: { callback: v => v + ' MT' } } }
            }
        });

        // Consultas por Tipo
        new Chart(document.getElementById('typeChart'), {
            type: 'doughnut',
            data: {
                labels: ['Presencial', 'Teleconsulta', 'Domicílio'],
                datasets: [{
                    data: [
                        {{ $consultationsByType['presencial'] ?? 0 }},
                        {{ $consultationsByType['teleconsulta'] ?? 0 }},
                        {{ $consultationsByType['domicilio'] ?? 0 }}
                    ],
                    backgroundColor: ['#3b82f6', '#8b5cf6', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Receita Diária
        new Chart(document.getElementById('dailyChart'), {
            type: 'bar',
            data: {
                labels: @json($dailyLabels),
                datasets: [{
                    label: 'Receita (MT)',
                    data: @json($dailyRevenue),
                    backgroundColor: '#10b981',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { callback: v => v + ' MT' } } }
            }
        });

        // Pacientes Novos
        new Chart(document.getElementById('patientsChart'), {
            type: 'line',
            data: {
                labels: @json($newPatientsLabels),
                datasets: [{
                    label: 'Novos Pacientes',
                    data: @json($newPatientsByMonth),
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

        // Consultas por Dia da Semana
        new Chart(document.getElementById('weekdayChart'), {
            type: 'bar',
            data: {
                labels: @json($weekdayLabels),
                datasets: [{
                    label: 'Consultas',
                    data: @json($consultationsByWeekday),
                    backgroundColor: '#6366f1',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    </script>

</x-layouts.admin>