<x-layouts.admin title="Dashboard">

    <!-- Saudação -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Olá, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-gray-600">Bem-vindo ao painel administrativo do Makombe.</p>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Pacientes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_patients'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar text-amber-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Consultas Hoje</p>
                    <p class="text-2xl font-bold text-amber-700">{{ $stats['today_consultations'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-coins text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Receita Hoje</p>
                    <p class="text-2xl font-bold text-green-700">{{ number_format($stats['today_revenue'], 2, ',', '.') }} MT</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Receita Mês</p>
                    <p class="text-2xl font-bold text-purple-700">{{ number_format($stats['month_revenue'], 2, ',', '.') }} MT</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Consultas de Hoje -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-calendar-check text-blue-600"></i>
                Consultas de Hoje
            </h3>
            <a href="{{ route('consultations.index') }}" class="text-sm text-blue-600 hover:underline">Ver todas</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($todayConsultations as $consultation)
                <div class="p-4 hover:bg-gray-50 transition flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-stethoscope text-blue-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900">
                            {{ $consultation->scheduled_at->format('H:i') }} - {{ $consultation->patient->full_name }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-user-md mr-1"></i> {{ $consultation->doctor->name ?? 'Médico' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $consultation->type === 'presencial' ? '🏥' : ($consultation->type === 'teleconsulta' ? '💻' : '🏠') }}
                            {{ ucfirst($consultation->type) }}
                        </p>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                        {{ ucfirst($consultation->status) }}
                    </span>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-calendar text-4xl text-gray-300 mb-3"></i>
                    <p>Nenhuma consulta agendada para hoje</p>
                </div>
            @endforelse
        </div>
    </div>

</x-layouts.admin>