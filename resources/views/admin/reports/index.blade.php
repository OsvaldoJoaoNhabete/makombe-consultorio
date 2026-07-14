<x-layouts.admin title="Relatórios Gerenciais Avançados">

    {{-- CSS AVANÇADO PARA IMPRESSÃO E GRÁFICOS --}}
    <style>
        .tab-btn { transition: all 0.3s; }
        .tab-btn.active { background-color: #059669; color: white; }
        .period-btn { transition: all 0.2s; }
        .period-btn.active { background-color: #059669; color: white; border-color: #059669; }
        
        @media print {
            body * { visibility: hidden; }
            #printableArea, #printableArea * { visibility: visible; }
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 15px;
                background: white;
            }
            .no-print, button, nav, aside, header, footer, .sidebar, .fixed, input, select {
                display: none !important;
            }
            table { width: 100%; border-collapse: collapse; font-size: 11px; }
            th, td { border: 1px solid #000; padding: 5px; text-align: left; }
            th { background-color: #f3f4f6 !important; -webkit-print-color-adjust: exact; font-weight: bold; }
            .print-header {
                display: flex !important;
                justify-content: space-between;
                align-items: center;
                border-bottom: 3px solid #000;
                padding-bottom: 15px;
                margin-bottom: 20px;
                visibility: visible !important;
            }
            .page-break { page-break-before: always; }
        }
        .print-header { display: none; }
    </style>

    <div id="printableArea">
        
        {{-- CABEÇALHO PARA IMPRESSÃO --}}
        <div class="print-header">
            <div>
                <h1 style="font-size: 28px; margin: 0; color: #000;">MAKOMBE CONSULTÓRIO MÉDICO</h1>
                <p style="margin: 5px 0 0 0; color: #333;">Relatório Gerencial - {{ ucfirst(str_replace('_', ' ', $reportType)) }}</p>
                <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">Período: {{ $label }} | Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
            </div>
            <div style="text-align: right;">
                @if(file_exists(public_path('images/logo-mcm.png')))
                    <img src="{{ public_path('images/logo-mcm.png') }}" alt="Logo" style="height: 70px;">
                @endif
            </div>
        </div>

        {{-- CONTEÚDO PRINCIPAL --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 no-print">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2"> Relatórios Gerenciais</h1>
                <p class="text-gray-600">Período: <span class="font-semibold text-emerald-700">{{ $label }}</span></p>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="px-6 py-3 bg-gray-800 hover:bg-gray-900 text-white font-bold rounded-xl shadow-lg transition flex items-center gap-2">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </button>
            </div>
        </div>

        {{-- SELETOR DE TIPO DE RELATÓRIO --}}
        <div class="bg-white rounded-xl shadow-sm border p-4 mb-6 no-print">
            <div class="flex flex-wrap gap-2 mb-4">
                <a href="{{ route('reports.index', array_merge(request()->except('type'), ['type' => 'dashboard'])) }}" 
                   class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold {{ $reportType === 'dashboard' ? 'active bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📈 Dashboard
                </a>
                <a href="{{ route('reports.index', array_merge(request()->except('type'), ['type' => 'consultations'])) }}" 
                   class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold {{ $reportType === 'consultations' ? 'active bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    🏥 Consultas
                </a>
                <a href="{{ route('reports.index', array_merge(request()->except('type'), ['type' => 'patients'])) }}" 
                   class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold {{ $reportType === 'patients' ? 'active bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                     Pacientes
                </a>
                <a href="{{ route('reports.index', array_merge(request()->except('type'), ['type' => 'payments'])) }}" 
                   class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold {{ $reportType === 'payments' ? 'active bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    💰 Pagamentos
                </a>
                <a href="{{ route('reports.index', array_merge(request()->except('type'), ['type' => 'financial'])) }}" 
                   class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold {{ $reportType === 'financial' ? 'active bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📊 Financeiro
                </a>
                <a href="{{ route('reports.index', array_merge(request()->except('type'), ['type' => 'users'])) }}" 
                   class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold {{ $reportType === 'users' ? 'active bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    👨‍⚕️ Utilizadores
                </a>
                <a href="{{ route('reports.index', array_merge(request()->except('type'), ['type' => 'quotes'])) }}" 
                   class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold {{ $reportType === 'quotes' ? 'active bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📋 Cotações
                </a>
            </div>

            {{-- FILTROS DE PERÍODO --}}
            <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap gap-2 items-end">
                <input type="hidden" name="type" value="{{ $reportType }}">
                
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Período Rápido</label>
                    <select name="period" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hoje</option>
                        <option value="yesterday" {{ $period === 'yesterday' ? 'selected' : '' }}>Ontem</option>
                        <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Esta Semana</option>
                        <option value="last_week" {{ $period === 'last_week' ? 'selected' : '' }}>Semana Passada</option>
                        <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Este Mês</option>
                        <option value="last_month" {{ $period === 'last_month' ? 'selected' : '' }}>Mês Passado</option>
                        <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Este Ano</option>
                        <option value="last_year" {{ $period === 'last_year' ? 'selected' : '' }}>Ano Passado</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Data Inicial</label>
                    <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Data Final</label>
                    <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>

                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg text-sm">
                    <i class="fas fa-filter mr-1"></i> Filtrar
                </button>

                <a href="{{ route('reports.index', ['type' => $reportType]) }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg text-sm">
                    <i class="fas fa-redo mr-1"></i> Limpar
                </a>
            </form>
        </div>

        {{-- CARDS DE ESTATÍSTICAS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-l-4 border-l-emerald-500">
                <p class="text-xs text-gray-500 uppercase font-semibold">Receita Total (Geral)</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_revenue'], 2, ',', '.') }} MT</p>
                <p class="text-xs text-emerald-600 mt-1">Período: {{ number_format($stats['period_revenue'], 2, ',', '.') }} MT</p>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-l-4 border-l-blue-500">
                <p class="text-xs text-gray-500 uppercase font-semibold">Total de Consultas</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_consultations'] }}</p>
                <p class="text-xs text-blue-600 mt-1">Período: {{ $stats['period_consultations'] }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-l-4 border-l-purple-500">
                <p class="text-xs text-gray-500 uppercase font-semibold">Total de Pacientes</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_patients'] }}</p>
                <p class="text-xs text-purple-600 mt-1">Novos no período: {{ $stats['period_new_patients'] }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-l-4 border-l-amber-500">
                <p class="text-xs text-gray-500 uppercase font-semibold">Pagamentos Pendentes</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['pending_payments'], 2, ',', '.') }} MT</p>
                <p class="text-xs text-amber-600 mt-1">Período: {{ number_format($stats['period_pending'], 2, ',', '.') }} MT</p>
            </div>
        </div>

        {{-- CONTEÚDO ESPECÍFICO POR TIPO DE RELATÓRIO --}}
        
        @if($reportType === 'dashboard')
            {{-- GRÁFICOS --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="font-bold text-gray-900 mb-4">📈 Receita Diária (Últimos 7 Dias)</h3>
                    <canvas id="revenueChart" height="200"></canvas>
                </div>
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="font-bold text-gray-900 mb-4">🏥 Consultas Diárias (Últimos 7 Dias)</h3>
                    <canvas id="consultationsChart" height="200"></canvas>
                </div>
            </div>

            {{-- TOP MÉDICOS --}}
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-6">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h3 class="font-bold text-gray-900">👨‍️ Top 5 Médicos (Consultas no Período)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Médico</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Total Consultas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($data['topDoctors'] ?? [] as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm font-bold text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-3 text-sm">{{ $item->doctor->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-3 text-center">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">{{ $item->count }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500">Nenhum dado disponível</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- CONSULTAS RECENTES --}}
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h3 class="font-bold text-gray-900"> Consultas Recentes</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data/Hora</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Paciente</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Médico</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tipo</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($data['recentConsultations'] ?? [] as $consult)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm">{{ $consult->scheduled_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-3 text-sm font-medium">{{ $consult->patient->full_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-3 text-sm">{{ $consult->doctor->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-3 text-sm capitalize">{{ $consult->type }}</td>
                                    <td class="px-6 py-3 text-center">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $consult->status === 'concluida' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $consult->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Nenhuma consulta</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif($reportType === 'consultations')
            {{-- RELATÓRIO DE CONSULTAS --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Consultas por Tipo</h3>
                    <canvas id="consultTypeChart" height="200"></canvas>
                </div>
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Consultas por Status</h3>
                    <canvas id="consultStatusChart" height="200"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-6 page-break">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h3 class="font-bold text-gray-900">📋 Todas as Consultas do Período</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data/Hora</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Paciente</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Médico</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Seguradora</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Valor</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($data['consultations'] ?? [] as $consult)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm">{{ $consult->scheduled_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-3 text-sm font-medium">{{ $consult->patient->full_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-3 text-sm">{{ $consult->doctor->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-3 text-sm capitalize">{{ $consult->type }}</td>
                                    <td class="px-6 py-3 text-sm">{{ $consult->insurance->name ?? 'Particular' }}</td>
                                    <td class="px-6 py-3 text-sm text-right font-bold">{{ number_format($consult->total_amount, 2, ',', '.') }} MT</td>
                                    <td class="px-6 py-3 text-center">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $consult->status === 'concluida' ? 'bg-green-100 text-green-800' : ($consult->status === 'cancelada' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $consult->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">Nenhuma consulta</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif($reportType === 'patients')
            {{-- RELATÓRIO DE PACIENTES --}}
            <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
                <h3 class="font-bold text-gray-900 mb-4">Pacientes por Género</h3>
                <canvas id="patientsGenderChart" height="200"></canvas>
            </div>

            <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-6 page-break">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h3 class="font-bold text-gray-900">👥 Todos os Pacientes do Período</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">NID</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nome Completo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Telefone</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Género</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data Registo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($data['patients'] ?? [] as $patient)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm font-mono">{{ $patient->nid }}</td>
                                    <td class="px-6 py-3 text-sm font-medium">{{ $patient->full_name }}</td>
                                    <td class="px-6 py-3 text-sm">+258 {{ $patient->phone }}</td>
                                    <td class="px-6 py-3 text-sm">{{ $patient->email }}</td>
                                    <td class="px-6 py-3 text-sm capitalize">{{ $patient->gender }}</td>
                                    <td class="px-6 py-3 text-sm">{{ $patient->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Nenhum paciente</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h3 class="font-bold text-gray-900">🏆 Top 10 Pacientes (Mais Consultas)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Paciente</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Telefone</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Total Consultas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($data['topPatients'] ?? [] as $index => $patient)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm font-bold text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-3 text-sm font-medium">{{ $patient->full_name }}</td>
                                    <td class="px-6 py-3 text-sm">+258 {{ $patient->phone }}</td>
                                    <td class="px-6 py-3 text-center">
                                        <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-bold rounded-full">{{ $patient->consultations_count }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Nenhum dado</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif($reportType === 'payments')
            {{-- RELATÓRIO DE PAGAMENTOS --}}
            <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
                <h3 class="font-bold text-gray-900 mb-4">Pagamentos por Método</h3>
                <canvas id="paymentMethodChart" height="200"></canvas>
            </div>

            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h3 class="font-bold text-gray-900">💳 Todos os Pagamentos do Período</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Paciente</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Médico</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Método</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Valor</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($data['payments'] ?? [] as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-3 text-sm font-medium">{{ $payment->patient->full_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-3 text-sm">{{ $payment->consultation?->doctor?->name ?? '-' }}</td>
                                    <td class="px-6 py-3 text-sm capitalize">{{ $payment->method }}</td>
                                    <td class="px-6 py-3 text-sm text-right font-bold">{{ number_format($payment->amount, 2, ',', '.') }} MT</td>
                                    <td class="px-6 py-3 text-center">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $payment->status === 'confirmado' ? 'bg-green-100 text-green-800' : ($payment->status === 'cancelado' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Nenhum pagamento</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif($reportType === 'financial')
            {{-- RELATÓRIO FINANCEIRO --}}
            <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
                <h3 class="font-bold text-gray-900 mb-4">Receita Diária</h3>
                <canvas id="dailyRevenueChart" height="200"></canvas>
            </div>

            @if(isset($data['insuranceRevenue']) && $data['insuranceRevenue']->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-6 page-break">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h3 class="font-bold text-gray-900"> Receita por Seguradora</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Seguradora</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Total Consultas</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Valor Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($data['insuranceRevenue'] as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm font-medium">{{ $item->insurance->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-3 text-center">{{ $item->count }}</td>
                                    <td class="px-6 py-3 text-sm text-right font-bold">{{ number_format($item->total, 2, ',', '.') }} MT</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        @elseif($reportType === 'users')
            {{-- RELATÓRIO DE UTILIZADORES --}}
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h3 class="font-bold text-gray-900">👨‍️ Todos os Utilizadores do Sistema</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nome</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Função</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Consultas</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data Registo</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($data['users'] ?? [] as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm font-medium">{{ $user->name }}</td>
                                    <td class="px-6 py-3 text-sm">{{ $user->email }}</td>
                                    <td class="px-6 py-3 text-sm">
                                        @foreach($user->getRoleNames() as $role)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">{{ $role }}</span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-3 text-center">{{ $user->consultations_count ?? 0 }}</td>
                                    <td class="px-6 py-3 text-sm">{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-3 text-center">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Nenhum utilizador</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif($reportType === 'quotes')
            {{-- RELATÓRIO DE COTAÇÕES --}}
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h3 class="font-bold text-gray-900"> Todas as Cotações do Período</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nº</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Paciente</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Itens</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($data['quotes'] ?? [] as $quote)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm font-mono">#{{ str_pad($quote->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-3 text-sm font-medium">{{ $quote->patient->full_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-3 text-sm">{{ $quote->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-3 text-sm">{{ $quote->items->count() }}</td>
                                    <td class="px-6 py-3 text-sm text-right font-bold">{{ number_format($quote->final_amount, 2, ',', '.') }} MT</td>
                                    <td class="px-6 py-3 text-center">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $quote->status === 'aprovada' ? 'bg-green-100 text-green-800' : ($quote->status === 'recusada' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ ucfirst($quote->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Nenhuma cotação</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div> {{-- Fim do printableArea --}}

        {{-- SCRIPTS PARA GRÁFICOS (CORRIGIDOS) --}}
    @if($reportType === 'dashboard' || $reportType === 'consultations' || $reportType === 'patients' || $reportType === 'payments' || $reportType === 'financial')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráficos do Dashboard
        @if($reportType === 'dashboard')
            new Chart(document.getElementById('revenueChart'), {
                type: 'bar',
                data: {
                    labels: @json($data['dailyLabels'] ?? []),
                    datasets: [{
                        label: 'Receita (MT)',
                        data: @json($data['dailyRevenue'] ?? []),
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: '#059669',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });

            new Chart(document.getElementById('consultationsChart'), {
                type: 'line',
                data: {
                    labels: @json($data['dailyLabels'] ?? []),
                    datasets: [{
                        label: 'Consultas',
                        data: @json($data['dailyConsultations'] ?? []),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });
        @endif

        // Gráficos de Consultas
        @if($reportType === 'consultations')
            new Chart(document.getElementById('consultTypeChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($data['consultTypeLabels'] ?? []),
                    datasets: [{
                        data: @json($data['consultTypeData'] ?? []),
                        backgroundColor: ['#3b82f6', '#8b5cf6', '#f59e0b', '#10b981', '#ef4444']
                    }]
                },
                options: { responsive: true }
            });

            new Chart(document.getElementById('consultStatusChart'), {
                type: 'pie',
                data: {
                    labels: @json($data['consultStatusLabels'] ?? []),
                    datasets: [{
                        data: @json($data['consultStatusData'] ?? []),
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#6b7280']
                    }]
                },
                options: { responsive: true }
            });
        @endif

        // Gráfico de Pacientes por Género
        @if($reportType === 'patients')
            new Chart(document.getElementById('patientsGenderChart'), {
                type: 'bar',
                data: {
                    labels: @json($data['patientsGenderLabels'] ?? []),
                    datasets: [{
                        label: 'Total',
                        data: @json($data['patientsGenderData'] ?? []),
                        backgroundColor: ['#ec4899', '#3b82f6', '#6b7280']
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });
        @endif

        // Gráfico de Pagamentos por Método
        @if($reportType === 'payments')
            new Chart(document.getElementById('paymentMethodChart'), {
                type: 'bar',
                data: {
                    labels: @json($data['paymentMethodLabels'] ?? []),
                    datasets: [{
                        label: 'Total (MT)',
                        data: @json($data['paymentMethodData'] ?? []),
                        backgroundColor: '#10b981'
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });
        @endif

        // Gráfico de Receita Diária (Financeiro)
        @if($reportType === 'financial')
            new Chart(document.getElementById('dailyRevenueChart'), {
                type: 'line',
                data: {
                    labels: @json($data['dailyRevenueLabels'] ?? []),
                    datasets: [{
                        label: 'Receita (MT)',
                        data: @json($data['dailyRevenueData'] ?? []),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: { responsive: true, scales: { y: { beginAtZero: true } } }
            });
        @endif
    </script>
    @endif

</x-layouts.admin>