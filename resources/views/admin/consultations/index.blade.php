<x-layouts.admin title="Consultas">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">📅 Consultas</h1>
            <p class="text-gray-600">Gestão de consultas</p>
        </div>
        <a href="{{ route('consultations.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-1"></i> Nova Consulta
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data/Hora</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Paciente</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Médico</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($consultations as $c)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm">{{ $c->scheduled_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 font-medium">{{ $c->patient->full_name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $c->doctor->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            {{ $c->type === 'presencial' ? '🏥' : ($c->type === 'teleconsulta' ? '💻' : '🏠') }}
                            {{ ucfirst($c->type) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($c->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('consultations.show', $c->id) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-calendar text-4xl text-gray-300 mb-3"></i>
                            <p>Nenhuma consulta encontrada</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($consultations->hasPages())
            <div class="px-6 py-4 border-t bg-gray-50">{{ $consultations->links() }}</div>
        @endif
    </div>
</x-layouts.admin>