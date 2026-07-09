<x-layouts.admin title="Atividades">

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">📋 Atividades do Sistema</h1>
            <p class="text-gray-600">Logs de atividades dos pacientes</p>
        </div>
        <form method="GET" action="{{ route('activities.clearOld') }}" 
              onsubmit="return confirm('Limpar logs com mais de 90 dias?');"
              class="inline">
            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium">
                <i class="fas fa-broom mr-1"></i> Limpar Antigos
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        @if($activities->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($activities as $activity)
                    @php
                        $icon = match($activity->action) {
                            'login' => 'fa-sign-in-alt',
                            'logout' => 'fa-sign-out-alt',
                            'consulta_agendada' => 'fa-calendar-plus',
                            'consulta_concluida' => 'fa-check-circle',
                            'consulta_cancelada' => 'fa-times-circle',
                            'perfil_atualizado' => 'fa-user-edit',
                            default => 'fa-info-circle',
                        };
                        $color = match($activity->action) {
                            'login' => 'bg-green-100 text-green-600',
                            'logout' => 'bg-gray-100 text-gray-600',
                            'consulta_agendada' => 'bg-blue-100 text-blue-600',
                            'consulta_concluida' => 'bg-purple-100 text-purple-600',
                            'consulta_cancelada' => 'bg-red-100 text-red-600',
                            default => 'bg-gray-100 text-gray-600',
                        };
                    @endphp
                    <div class="p-4 hover:bg-gray-50 transition">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 {{ $color }}">
                                <i class="fas {{ $icon }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between flex-wrap gap-2">
                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            {{ ucfirst(str_replace('_', ' ', $activity->action)) }}
                                        </p>
                                        @if($activity->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $activity->description }}</p>
                                        @endif
                                        @if($activity->patient)
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class="fas fa-user mr-1"></i>
                                                <strong>{{ $activity->patient->full_name }}</strong>
                                                @if($activity->user)
                                                    <span class="ml-2">• Por: {{ $activity->user->name }}</span>
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right text-xs text-gray-500">
                                        <p>{{ $activity->created_at->format('d/m/Y H:i') }}</p>
                                        <p class="mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($activities->hasPages())
                <div class="px-6 py-4 border-t bg-gray-50">
                    {{ $activities->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <i class="fas fa-history text-6xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Nenhuma atividade registada</h4>
                <p class="text-gray-500">As atividades do sistema aparecerão aqui.</p>
            </div>
        @endif
    </div>

</x-layouts.admin>