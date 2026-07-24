<x-layouts.admin title="Detalhes do Paciente">

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Detalhes do Paciente</h1>
            <p class="text-gray-600">Informações completas e histórico clínico</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('patients.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-xl shadow-lg transition">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            @canany(['Administrador', 'Gerente'])
                <a href="{{ route('patients.edit', $patient->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg transition">
                    <i class="fas fa-edit"></i> Editar
                </a>
            @endcanany
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
            <p class="text-sm text-green-700 font-medium"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Coluna Esquerda: Foto e Resumo -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                <div class="relative w-32 h-32 mx-auto mb-4">
                    @if($patient->hasPhoto())
                        <img src="{{ $patient->getPhotoUrl() }}" alt="Foto" class="w-full h-full rounded-full object-cover border-4 border-purple-100">
                    @else
                        <div class="w-full h-full rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white text-4xl font-bold border-4 border-purple-100">
                            {{ $patient->getInitial() }}
                        </div>
                    @endif
                </div>
                
                <h2 class="text-xl font-bold text-gray-900">{{ $patient->full_name }}</h2>
                <p class="text-purple-600 font-medium text-sm mb-4">NID: {{ $patient->nid }}</p>
                
                <div class="border-t border-gray-100 pt-4 space-y-3 text-left">
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="fas fa-birthday-cake text-purple-500 w-5"></i>
                        <span>{{ $patient->birth_date->format('d/m/Y') }} ({{ $patient->age }} anos)</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="fas fa-{{ $patient->gender === 'masculino' ? 'mars' : 'venus' }} text-purple-500 w-5"></i>
                        <span>{{ ucfirst($patient->gender) }}</span>
                    </div>
                    @if($patient->blood_type)
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="fas fa-tint text-red-500 w-5"></i>
                        <span>Tipo Sanguíneo: <strong>{{ $patient->blood_type }}</strong></span>
                    </div>
                    @endif
                    @if($patient->insurance)
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="fas fa-shield-alt text-purple-500 w-5"></i>
                        <span>{{ $patient->insurance->name }}</span>
                    </div>
                    @endif
                </div>

                <!-- Aviso de Privacidade para Médicos/Enfermeiros -->
                @if($isRestricted)
                    <div class="mt-4 bg-amber-50 border border-amber-200 rounded-lg p-3">
                        <p class="text-xs text-amber-800 flex items-start gap-2">
                            <i class="fas fa-lock mt-0.5"></i>
                            <span>Os dados de contacto estão protegidos por políticas de privacidade.</span>
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Coluna Direita: Informações Detalhadas -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Dados Pessoais -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-id-card text-purple-600"></i> Dados Pessoais
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Nome Completo</p>
                        <p class="text-gray-900 font-medium">{{ $patient->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">NID</p>
                        <p class="text-gray-900 font-medium">{{ $patient->nid }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Número de BI</p>
                        <p class="text-gray-900 font-medium">{{ $patient->bi_number ?? 'Não informado' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Data de Nascimento</p>
                        <p class="text-gray-900 font-medium">{{ $patient->birth_date->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Contactos (Protegido para Médicos/Enfermeiros) -->
            @if(!$isRestricted)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-address-book text-purple-600"></i> Contactos
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Telemóvel</p>
                            <p class="text-gray-900 font-medium">+258 {{ $patient->phone }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Email</p>
                            <p class="text-gray-900 font-medium">{{ $patient->email ?? 'Não informado' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs text-gray-500 uppercase font-semibold">Morada</p>
                            <p class="text-gray-900 font-medium">{{ $patient->address ?? 'Não informada' }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-amber-50 rounded-xl border border-amber-200 p-6">
                    <h3 class="text-lg font-bold text-amber-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-lock text-amber-600"></i> Contactos Protegidos
                    </h3>
                    <p class="text-sm text-amber-700">
                        Os dados de contacto deste paciente estão protegidos por políticas de privacidade. 
                        Contacte a receção ou administração se necessitar desta informação para atendimento.
                    </p>
                </div>
            @endif

            <!-- Histórico Médico -->
            @if($patient->medical_history)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-notes-medical text-purple-600"></i> Histórico Médico
                    </h3>
                    <p class="text-gray-700">{{ $patient->medical_history }}</p>
                </div>
            @endif

            <!-- Contacto de Emergência -->
            @if($patient->emergency_contact_name && !$isRestricted)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-ambulance text-purple-600"></i> Contacto de Emergência
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Nome</p>
                            <p class="text-gray-900 font-medium">{{ $patient->emergency_contact_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Parentesco</p>
                            <p class="text-gray-900 font-medium">{{ $patient->emergency_contact_relation ?? 'Não informado' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Telefone</p>
                            <p class="text-gray-900 font-medium">+258 {{ $patient->emergency_contact_phone }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Histórico de Consultas -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-calendar-check text-purple-600"></i> Consultas Recentes
                </h3>
                
                @if($recentConsultations->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentConsultations as $consultation)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                                        <i class="fas fa-stethoscope text-purple-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ \Carbon\Carbon::parse($consultation->scheduled_at)->format('d/m/Y H:i') }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $consultation->doctor->name ?? 'Médico não atribuído' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($consultation->type === 'teleconsulta')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            <i class="fas fa-video"></i> Teleconsulta
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            <i class="fas fa-hospital"></i> Presencial
                                        </span>
                                    @endif
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $consultation->status === 'concluida' ? 'bg-green-100 text-green-800' : 
                                           ($consultation->status === 'cancelada' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $consultation->status)) }}
                                    </span>
                                    <a href="{{ route('consultations.show', $consultation->id) }}" class="px-3 py-1.5 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg text-xs">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Nenhuma consulta registada para este paciente.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-layouts.admin>