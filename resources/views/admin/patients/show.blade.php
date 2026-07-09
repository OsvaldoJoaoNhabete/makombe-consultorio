<x-layouts.admin title="Paciente: {{ $patient->full_name }}">
    <div class="mb-4">
        <a href="{{ route('patients.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-1"></i> Voltar
        </a>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h1 class="text-2xl font-bold mb-4">{{ $patient->full_name }}</h1>
        <div class="grid grid-cols-2 gap-4">
            <div><strong>NID:</strong> {{ $patient->nid }}</div>
            <div><strong>BI:</strong> {{ $patient->bi_number ?? '-' }}</div>
            <div><strong>Data Nascimento:</strong> {{ $patient->birth_date?->format('d/m/Y') }}</div>
            <div><strong>Género:</strong> {{ ucfirst($patient->gender) }}</div>
            <div><strong>Telefone:</strong> +258 {{ $patient->phone }}</div>
            <div><strong>Email:</strong> {{ $patient->email }}</div>
            <div class="col-span-2"><strong>Endereço:</strong> {{ $patient->address ?? '-' }}</div>
            <div class="col-span-2"><strong>Histórico:</strong> {{ $patient->medical_history ?? '-' }}</div>
        </div>
    </div>
</x-layouts.admin>