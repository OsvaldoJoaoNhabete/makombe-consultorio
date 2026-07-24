<x-layouts.admin title="Dashboard Financeiro">

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Financeiro Geral</h1>
        <p class="text-gray-600">
            Visão geral das receitas - {{ \Carbon\Carbon::now()->locale('pt_PT')->isoFormat('MMMM YYYY') }}
        </p>
    </div>

    <!-- Estatísticas Gerais -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Receita do Mês</p>
                    <p class="text-2xl font-bold text-purple-700">{{ number_format($stats['monthly_revenue'], 2) }} MT</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Receita Acumulada</p>
                    <p class="text-2xl font-bold text-green-700">{{ number_format($stats['accumulated_revenue'], 2) }} MT</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-blue-600 uppercase font-semibold">Consultas no Mês</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $stats['monthly_consultations'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-amber-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-amber-600 uppercase font-semibold">Pagamentos Pendentes</p>
                    <p class="text-2xl font-bold text-amber-700">{{ $stats['pending_payments'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ranking de Receita por Médico -->
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-trophy text-purple-600"></i> 
            Receita por Médico (Este Mês)
        </h2>
        
        @if($revenueByDoctor->count() > 0)
            <div class="space-y-3">
                @foreach($revenueByDoctor as $index => $item)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $item->doctor->name ?? 'Médico não atribuído' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $item->doctor->specialty->name ?? 'Clínica Geral' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-purple-700">{{ number_format($item->total, 2) }} MT</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-chart-bar text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">Nenhuma receita registada neste mês.</p>
            </div>
        @endif
    </div>

</x-layouts.admin>