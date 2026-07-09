<x-layouts.admin title="Notificações">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">🔔 Notificações</h1>
        <p class="text-gray-600">Central de notificações</p>
    </div>
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl shadow-sm border"><p class="text-xs text-gray-500">Total</p><p class="text-2xl font-bold">{{ $stats['total'] }}</p></div>
        <div class="bg-white p-4 rounded-xl shadow-sm border"><p class="text-xs text-red-500">Não Lidas</p><p class="text-2xl font-bold text-red-700">{{ $stats['nao_lidas'] }}</p></div>
        <div class="bg-white p-4 rounded-xl shadow-sm border"><p class="text-xs text-green-500">Lidas</p><p class="text-2xl font-bold text-green-700">{{ $stats['lidas'] }}</p></div>
        <div class="bg-white p-4 rounded-xl shadow-sm border"><p class="text-xs text-purple-500">Hoje</p><p class="text-2xl font-bold text-purple-700">{{ $stats['hoje'] }}</p></div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border p-6">
        @forelse($notifications as $n)
            <div class="p-3 border-b flex justify-between items-center">
                <div>
                    <p class="font-semibold">{{ $n->data['title'] ?? 'Notificação' }}</p>
                    <p class="text-sm text-gray-600">{{ $n->data['message'] ?? '' }}</p>
                    <p class="text-xs text-gray-400">{{ $n->created_at->diffForHumans() }}</p>
                </div>
                @if(!$n->read_at)
                    <form method="POST" action="{{ route('notifications.markAsRead', $n->id) }}">
                        @csrf
                        <button class="px-3 py-1 bg-green-100 text-green-700 rounded text-xs">Marcar como lida</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-center text-gray-500 py-8">Nenhuma notificação</p>
        @endforelse
        @if($notifications->hasPages())
            <div class="mt-4">{{ $notifications->links() }}</div>
        @endif
    </div>
</x-layouts.admin>