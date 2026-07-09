<x-layouts.admin title="{{ isset($patient) ? 'Editar Paciente' : 'Novo Paciente' }}">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h1 class="text-2xl font-bold mb-4">{{ isset($patient) ? 'Editar' : 'Novo' }} Paciente</h1>
        <p class="text-gray-600">Funcionalidade em desenvolvimento.</p>
        <a href="{{ route('patients.index') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg">Voltar</a>
    </div>
</x-layouts.admin>