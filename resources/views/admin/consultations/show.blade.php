<x-layouts.admin title="Consulta #{{ $consultation->id }}">
    <div class="mb-4">
        <a href="{{ route('consultations.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-1"></i> Voltar
        </a>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h1 class="text-2xl font-bold mb-4">Consulta #{{ $consultation->id }}</h1>
        <div class="grid grid-cols-2 gap-4">
            <div><strong>Data/Hora:</strong> {{ $consultation->scheduled_at->format('d/m/Y H:i') }}</div>
            <div><strong>Tipo:</strong> {{ ucfirst($consultation->type) }}</div>
            <div><strong>Paciente:</strong> {{ $consultation->patient->full_name ?? '-' }}</div>
            <div><strong>Médico:</strong> {{ $consultation->doctor->name ?? '-' }}</div>
            <div><strong>Status:</strong> {{ ucfirst($consultation->status) }}</div>
            <div><strong>Valor:</strong> {{ number_format($consultation->total_amount, 2, ',', '.') }} MT</div>
            @if($consultation->location)
                <div class="col-span-2"><strong>Local/Link:</strong> {{ $consultation->location }}</div>
            @endif
            @if($consultation->diagnosis)
                <div class="col-span-2"><strong>Diagnóstico:</strong> {{ $consultation->diagnosis }}</div>
            @endif
        </div>
    </div>
</x-layouts.admin>