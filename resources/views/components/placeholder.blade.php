<x-layouts.admin :title="$title ?? 'Página'">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-tools text-3xl text-blue-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $title ?? 'Página em Desenvolvimento' }}</h2>
        <p class="text-gray-600 mb-6">
            Esta funcionalidade está a ser desenvolvida e estará disponível em breve.
        </p>
        <a href="{{ route('dashboard') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition">
            <i class="fas fa-arrow-left"></i> Voltar ao Dashboard
        </a>
    </div>
</x-layouts.admin>