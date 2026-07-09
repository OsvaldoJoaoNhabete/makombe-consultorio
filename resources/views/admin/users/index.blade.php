<x-layouts.admin title="Utilizadores">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">👥 Utilizadores</h1>
        <a href="{{ route('users.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            <i class="fas fa-plus mr-1"></i> Novo Utilizador
        </a>
    </div>
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Função</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $u)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">{{ $u->name }}</td>
                        <td class="px-6 py-4 text-sm">{{ $u->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ $u->roles->first()?->name ?? 'Sem função' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $u->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $u->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('users.show', $u->id) }}" class="text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">Nenhum utilizador</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($users->hasPages())
            <div class="px-6 py-4 border-t bg-gray-50">{{ $users->links() }}</div>
        @endif
    </div>
</x-layouts.admin>