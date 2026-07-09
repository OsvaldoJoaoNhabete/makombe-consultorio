<x-layouts.admin title="Pacientes">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">👥 Pacientes</h1>
            <p class="text-gray-600">Gestão de pacientes do consultório</p>
        </div>
        <a href="{{ route('patients.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-1"></i> Novo Paciente
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">NID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Telefone</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($patients as $patient)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">{{ $patient->full_name }}</td>
                        <td class="px-6 py-4 text-sm font-mono">{{ $patient->nid }}</td>
                        <td class="px-6 py-4 text-sm">+258 {{ $patient->phone }}</td>
                        <td class="px-6 py-4 text-sm">{{ $patient->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $patient->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $patient->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('patients.show', $patient->id) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('patients.edit', $patient->id) }}" class="text-amber-600 hover:text-amber-800">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                            <p>Nenhum paciente registado</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($patients->hasPages())
            <div class="px-6 py-4 border-t bg-gray-50">{{ $patients->links() }}</div>
        @endif
    </div>
</x-layouts.admin>