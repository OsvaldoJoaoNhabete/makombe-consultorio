<x-layouts.admin title="{{ isset($consultation) ? 'Editar Consulta' : 'Nova Consulta' }}">

    <div class="mb-4">
        <a href="{{ route('consultations.index') }}" class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-800 font-medium">
            <i class="fas fa-arrow-left"></i> Voltar para consultas
        </a>
    </div>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            
            <div class="mb-6 pb-6 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    {{ isset($consultation) ? '✏️ Editar Consulta' : '📅 Nova Consulta' }}
                </h1>
                <p class="text-gray-600">Agende uma nova consulta médica para o paciente.</p>
            </div>

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <ul class="text-sm text-red-800 space-y-1">
                        @foreach($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" 
                  action="{{ isset($consultation) ? route('consultations.update', $consultation->id) : route('consultations.store') }}" 
                  class="space-y-6">
                @csrf
                @if(isset($consultation))
                    @method('PUT')
                @endif

                <!-- Paciente -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-purple-600 mr-1"></i> Paciente <span class="text-red-500">*</span>
                    </label>
                    <select name="patient_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        <option value="">Selecione um paciente...</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}" {{ old('patient_id', $consultation->patient_id ?? '') == $p->id ? 'selected' : '' }}>
                                {{ $p->full_name }} (NID: {{ $p->nid }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Médico -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user-md text-purple-600 mr-1"></i> Médico <span class="text-red-500">*</span>
                    </label>
                    <select name="doctor_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        <option value="">Selecione um médico...</option>
                        @foreach($doctors as $d)
                            <option value="{{ $d->id }}" {{ old('doctor_id', $consultation->doctor_id ?? '') == $d->id ? 'selected' : '' }}>
                                Dr(a). {{ $d->name }} {{ $d->specialty ? '- ' . $d->specialty->name : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Data/Hora e Tipo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar text-purple-600 mr-1"></i> Data e Hora <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="scheduled_at" 
                               value="{{ old('scheduled_at', isset($consultation) ? \Carbon\Carbon::parse($consultation->scheduled_at)->format('Y-m-d\TH:i') : '') }}" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag text-purple-600 mr-1"></i> Tipo <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white"
                                onchange="toggleTeleconsultationInfo(this.value)">
                            <option value="presencial" {{ old('type', $consultation->type ?? '') === 'presencial' ? 'selected' : '' }}>🏥 Presencial</option>
                            <option value="teleconsulta" {{ old('type', $consultation->type ?? '') === 'teleconsulta' ? 'selected' : '' }}>💻 Teleconsulta</option>
                            <option value="domicilio" {{ old('type', $consultation->type ?? '') === 'domicilio' ? 'selected' : '' }}>🏠 Domicílio</option>
                        </select>
                    </div>
                </div>

                <!-- Info Teleconsulta -->
                <div id="teleconsultationInfo" class="hidden p-4 bg-purple-50 border border-purple-200 rounded-xl">
                    <p class="text-sm text-purple-800 flex items-start gap-2">
                        <i class="fas fa-video mt-0.5"></i>
                        <span>Para <strong>teleconsultas</strong>, um link Jitsi Meet será gerado automaticamente e associado a esta consulta.</span>
                    </p>
                </div>

                <!-- Status (apenas edição) -->
                @if(isset($consultation))
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-flag text-purple-600 mr-1"></i> Status
                        </label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                            <option value="agendada" {{ old('status', $consultation->status) === 'agendada' ? 'selected' : '' }}>📅 Agendada</option>
                            <option value="confirmada" {{ old('status', $consultation->status) === 'confirmada' ? 'selected' : '' }}>✅ Confirmada</option>
                            <option value="em_andamento" {{ old('status', $consultation->status) === 'em_andamento' ? 'selected' : '' }}>⏳ Em Andamento</option>
                            <option value="concluida" {{ old('status', $consultation->status) === 'concluida' ? 'selected' : '' }}>🏁 Concluída</option>
                            <option value="cancelada" {{ old('status', $consultation->status) === 'cancelada' ? 'selected' : '' }}>❌ Cancelada</option>
                            <option value="faltou" {{ old('status', $consultation->status) === 'faltou' ? 'selected' : '' }}>🚫 Faltou</option>
                        </select>
                    </div>
                @endif

                <!-- Seguradora e Valor -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-shield-alt text-purple-600 mr-1"></i> Seguradora
                        </label>
                        <select name="insurance_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                            <option value="">Nenhuma (Particular)</option>
                            @foreach($insurances as $i)
                                <option value="{{ $i->id }}" {{ old('insurance_id', $consultation->insurance_id ?? '') == $i->id ? 'selected' : '' }}>
                                    {{ $i->name }} (Cobertura: {{ $i->getCoverageFormatted() }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-coins text-purple-600 mr-1"></i> Valor Total (MT)
                        </label>
                        <input type="number" name="total_amount" step="0.01" min="0"
                               value="{{ old('total_amount', $consultation->total_amount ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="0.00">
                    </div>
                </div>

                <!-- Observações -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-purple-600 mr-1"></i> Observações / Motivo
                    </label>
                    <textarea name="notes" rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500"
                              placeholder="Ex: Primeira consulta de rotina, dor abdominal...">{{ old('notes', $consultation->notes ?? '') }}</textarea>
                </div>

                <!-- Botões -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="flex-1 py-3 px-4 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl transition flex items-center justify-center gap-2 shadow-md">
                        <i class="fas fa-save"></i> {{ isset($consultation) ? 'Atualizar' : 'Agendar' }} Consulta
                    </button>
                    <a href="{{ route('consultations.index') }}" 
                       class="flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleTeleconsultationInfo(type) {
            const info = document.getElementById('teleconsultationInfo');
            if (type === 'teleconsulta') {
                info.classList.remove('hidden');
            } else {
                info.classList.add('hidden');
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            toggleTeleconsultationInfo(document.querySelector('select[name="type"]').value);
        });
    </script>

</x-layouts.admin>