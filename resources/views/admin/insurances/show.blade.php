<x-layouts.admin title="Seguradora">
    <div class="mb-4"><a href="{{ route('insurances.index') }}" class="text-blue-600"><i class="fas fa-arrow-left mr-1"></i> Voltar</a></div>
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h1 class="text-2xl font-bold mb-4">{{ $insurance->name }}</h1>
        <div class="grid grid-cols-2 gap-4">
            <div><strong>Código:</strong> {{ $insurance->code ?? '-' }}</div>
            <div><strong>Cobertura:</strong> {{ $insurance->getCoverageFormatted() }}</div>
            <div><strong>Email:</strong> {{ $insurance->email ?? '-' }}</div>
            <div><strong>Telefone:</strong> {{ $insurance->phone ?? '-' }}</div>
            <div class="col-span-2"><strong>Endereço:</strong> {{ $insurance->address ?? '-' }}</div>
        </div>
    </div>
</x-layouts.admin>