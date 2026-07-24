<x-layouts.admin title="Meu Dashboard">

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            Olá, {{ Auth::user()->name }}! 👋
        </h1>
        <p class="text-gray-600">
            Aqui está o resumo das suas atividades - {{ \Carbon\Carbon::now()->locale('pt_PT')->isoFormat('MMMM YYYY') }}
        </p>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
            <p class="text-sm text-green-700 font-medium"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Estatísticas Financeiras Individuais (Sugestão 1) -->
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
                    <p class="text-xs text-blue-600 uppercase font-semibold">Consultas Este Mês</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $stats['monthly_consultations'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-amber-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-amber-600 uppercase font-semibold">Meus Pacientes</p>
                    <p class="text-2xl font-bold text-amber-700">{{ $stats['my_patients'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Consultas de Hoje -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-calendar-day text-purple-600"></i> 
            Consultas de Hoje ({{ $stats['today_consultations'] }})
        </h2>
        
        @if($todayConsultations->count() > 0)
            <div class="space-y-3">
                @foreach($todayConsultations as $consultation)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition {{ $consultation->is_urgent ? 'border-l-4 border-red-500' : '' }}">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-stethoscope text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $consultation->patient->full_name }}
                                    @if($consultation->is_urgent)
                                        <span class="ml-2 px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-ambulance"></i> URGENTE
                                        </span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($consultation->scheduled_at)->format('H:i') }} | 
                                    {{ $consultation->specialty->name ?? 'Clínica Geral' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $consultation->status === 'agendada' ? 'bg-blue-100 text-blue-800' : 
                                   ($consultation->status === 'confirmada' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($consultation->status) }}
                            </span>
                            <a href="{{ route('doctor.attend', $consultation->id) }}" class="px-3 py-1.5 bg-purple-600 text-white hover:bg-purple-700 rounded-lg text-xs font-medium">
                                <i class="fas fa-play"></i> Atender
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-calendar-check text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">Nenhuma consulta agendada para hoje.</p>
            </div>
        @endif
    </div>

    <!-- Próximas Consultas -->
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-clock text-purple-600"></i> 
            Próximas Consultas (7 dias)
        </h2>
        
        @if($upcomingConsultations->count() > 0)
            <div class="space-y-3">
                @foreach($upcomingConsultations as $consultation)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-user-injured text-indigo-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $consultation->patient->full_name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($consultation->scheduled_at)->format('d/m/Y H:i') }} | 
                                    {{ $consultation->specialty->name ?? 'Clínica Geral' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($consultation->type === 'teleconsulta')
                                <a href="{{ route('doctor.video.start', $consultation->id) }}" target="_blank" class="px-3 py-1.5 bg-green-50 text-green-600 hover:bg-green-100 rounded-lg text-xs">
                                    <i class="fas fa-video"></i> Videochamada
                                </a>
                            @endif
                            <a href="{{ route('doctor.show', $consultation->id) }}" class="px-3 py-1.5 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg text-xs">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">Nenhuma consulta agendada para os próximos 7 dias.</p>
            </div>
        @endif
    </div>

</x-layouts.admin>