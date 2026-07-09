<x-layouts.admin title="Seguradoras">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">🛡️ Seguradoras</h1>
        <a href="{{ route('insurances.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            <i class="fas fa-plus mr-1"></i> Nova Seguradora
        </a>
    </div>
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Código</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Cobertura</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($insurances as $i)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">{{ $i->name }}</td>
                        <td class="px-6 py-4 text-sm font-mono">{{ $i->code ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $i->getCoverageFormatted() }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $i->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $i->is_active ? 'Ativa' : 'Inativa' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('insurances.show', $i->id) }}" class="text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">Nenhuma seguradora</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($insurances->hasPages())
            <div class="px-6 py-4 border-t bg-gray-50">{{ $insurances->links() }}</div>
        @endif
    </div>
</x-layouts.admin>