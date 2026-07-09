<x-layouts.admin title="Utilizador">
    <div class="mb-4"><a href="{{ route('users.index') }}" class="text-blue-600"><i class="fas fa-arrow-left mr-1"></i> Voltar</a></div>
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h1 class="text-2xl font-bold mb-4">{{ $user->name }}</h1>
        <div class="grid grid-cols-2 gap-4">
            <div><strong>Email:</strong> {{ $user->email }}</div>
            <div><strong>Telefone:</strong> {{ $user->phone ?? '-' }}</div>
            <div><strong>Função:</strong> {{ $user->roles->first()?->name ?? '-' }}</div>
            <div><strong>Status:</strong> {{ $user->is_active ? 'Ativo' : 'Inativo' }}</div>
        </div>
    </div>
</x-layouts.admin>