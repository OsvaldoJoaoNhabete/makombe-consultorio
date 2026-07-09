<x-layouts.admin title="Atendimento">

    <div class="mb-4">
        <a href="{{ route('doctor.show', $consultation->id) }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="max-w-5xl mx-auto">
        
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-t-xl p-6 text-white">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-3xl">
                            {{ $consultation->type === 'presencial' ? '🏥' : ($consultation->type === 'teleconsulta' ? '💻' : '🏠') }}
                        </span>
                        <h1 class="text-2xl font-bold">Atendimento {{ ucfirst($consultation->type) }}</h1>
                    </div>
                    <p class="text-blue-100">
                        Paciente: <strong>{{ $consultation->patient->full_name }}</strong> • 
                        {{ $consultation->scheduled_at->format('d/m/Y \à\s H:i') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-blue-200 uppercase">Status</p>
                    <p class="font-bold text-lg">Em Atendimento</p>
                </div>
            </div>
        </div>

        @if($consultation->type === 'teleconsulta' && $consultation->location)
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 text-white">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-video text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Videochamada</h3>
                            <p class="text-green-100 text-sm">Clique para iniciar a teleconsulta</p>
                        </div>
                    </div>
                    <a href="{{ $consultation->location }}" target="_blank" 
                       class="px-6 py-3 bg-white text-green-700 font-bold rounded-xl hover:bg-green-50 transition flex items-center gap-2 shadow-lg">
                        <i class="fas fa-video"></i>
                        <span>Iniciar Videochamada</span>
                    </a>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('doctor.storeAttendance', $consultation->id) }}" 
              class="bg-white rounded-b-xl shadow-sm border border-gray-200 p-6 space-y-6">
            @csrf

            @if(!$consultation->clinical_notes)
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-comment-medical text-blue-600 mr-1"></i> Queixa Principal
                    </label>
                    <textarea name="clinical_notes" rows="3" 
                              placeholder="Descreva a queixa principal do paciente..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('clinical_notes') }}</textarea>
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Queixa Principal</p>
                    <p class="text-gray-800">{{ $consultation->clinical_notes }}</p>
                </div>
            @endif

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-stethoscope text-green-600 mr-1"></i> Diagnóstico *
                </label>
                <textarea name="diagnosis" rows="4" required
                          placeholder="Ex: Infecção respiratória aguda..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('diagnosis', $consultation->diagnosis) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-prescription text-purple-600 mr-1"></i> Prescrição
                </label>
                <textarea name="prescription" rows="5" 
                          placeholder="Ex:&#10;- Amoxicilina 500mg - 1 comprimido de 8/8h por 7 dias&#10;- Paracetamol 500mg - 1 comprimido de 6/6h se dor"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm">{{ old('prescription', $consultation->prescription) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-notes-medical text-amber-600 mr-1"></i> Observações
                </label>
                <textarea name="observations" rows="3" 
                          placeholder="Orientações, retorno, exames solicitados..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('observations', $consultation->observations) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-money-bill-wave text-green-600 mr-1"></i> Valor (MT)
                    </label>
                    <input type="number" name="total_amount" step="0.01" min="0"
                           value="{{ old('total_amount', $consultation->total_amount) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-shield-alt text-purple-600 mr-1"></i> Seguradora
                    </label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-700">
                        {{ $consultation->insurance?->name ?? 'Particular' }}
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
                <button type="submit" name="action" value="save" 
                        class="flex-1 py-3 px-4 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Salvar Rascunho
                </button>
                <button type="submit" name="action" value="complete" 
                        onclick="return confirm('Deseja finalizar esta consulta?');"
                        class="flex-1 py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> Finalizar e Concluir
                </button>
            </div>
        </form>
    </div>

</x-layouts.admin>