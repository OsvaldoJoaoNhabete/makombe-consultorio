<x-layouts.admin title="Atividades">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">📋 Atividades</h1>
        <p class="text-gray-600">Logs de atividades do sistema</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        @forelse($activities as $a)
            <div class="p-4 border-b">
                <p class="font-semibold">{{ $a->action }}</p>
                <p class="text-sm text-gray-600">{{ $a->description }}</p>
                <p class="text-xs text-gray-400">{{ $a->patient->full_name ?? '-' }} • {{ $a->created_at->diffForHumans() }}</p>
            </div>
        @empty
            <p class="text-center text-gray-500 py-8">Nenhuma atividade registada</p>
        @endforelse
        @if($activities->hasPages())
            <div class="p-4">{{ $activities->links() }}</div>
        @endif
    </div>
</x-layouts.admin>