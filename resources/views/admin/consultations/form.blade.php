<x-layouts.admin title="{{ isset($consultation) ? 'Editar Consulta' : 'Nova Consulta' }}">

    <div class="mb-4">
        <a href="{{ route('consultations.index') }}" class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-800 font-medium">
            <i class="fas fa-arrow-left"></i> Voltar para consultas
        </a>
    </div>

    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            
            <div class="mb-6 pb-6 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    {{ isset($consultation) ? '✏️ Editar Consulta' : '📅 Agendar Nova Consulta' }}
                </h1>
                <p class="text-gray-600">Preencha os dados clínicos e de pagamento para finalizar o agendamento.</p>
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

            <form method="POST" action="{{ isset($consultation) ? route('consultations.update', $consultation->id) : route('consultations.store') }}" class="space-y-8" id="consultationForm">
                @csrf
                @if(isset($consultation))
                    @method('PUT')
                @endif

                <!-- 1. PACIENTE E PROFISSIONAL -->
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-injured text-purple-600"></i> Paciente e Profissional
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Paciente <span class="text-red-500">*</span></label>
                            <select name="patient_id" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                                <option value="">Selecione um paciente...</option>
                                @foreach($patients as $p)
                                    <option value="{{ $p->id }}" {{ old('patient_id', $consultation->patient_id ?? '') == $p->id ? 'selected' : '' }}>
                                        {{ $p->full_name }} (NID: {{ $p->nid }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Especialidade <span class="text-red-500">*</span></label>
                            <select name="specialty_id" id="specialty_id" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white" onchange="filterDoctorsBySpecialty()">
                                <option value="">Selecione a área...</option>
                                @foreach($specialties as $spec)
                                    <option value="{{ $spec->id }}" {{ old('specialty_id', $consultation->specialty_id ?? '') == $spec->id ? 'selected' : '' }}>
                                        {{ $spec->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Médico <span class="text-red-500">*</span></label>
                            <select name="doctor_id" id="doctor_id" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                                <option value="">Selecione primeiro a especialidade...</option>
                                @foreach($doctors as $d)
                                    <option value="{{ $d->id }}" data-specialty="{{ $d->specialty_id }}" {{ old('doctor_id', $consultation->doctor_id ?? '') == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }} {{ $d->specialty ? '(' . $d->specialty->name . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                <!-- 2. DATA, HORA E MODALIDADE -->
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-purple-600"></i> Data, Hora e Modalidade
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Data e Hora <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', isset($consultation) ? \Carbon\Carbon::parse($consultation->scheduled_at)->format('Y-m-d\TH:i') : '') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                        
                        <div class="flex items-end pb-3">
                            <label class="flex items-center gap-3 p-3 border border-red-200 bg-red-50 rounded-xl cursor-pointer hover:bg-red-100 transition w-full">
                                <input type="checkbox" name="is_urgent" value="1" {{ old('is_urgent', $consultation->is_urgent ?? false) ? 'checked' : '' }} class="w-5 h-5 text-red-600 rounded focus:ring-red-500">
                                <div>
                                    <span class="block text-sm font-bold text-red-700"><i class="fas fa-ambulance mr-1"></i> Urgente</span>
                                    <span class="text-xs text-red-600">Prioridade no atendimento</span>
                                </div>
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Modalidade <span class="text-red-500">*</span></label>
                            <select name="type" id="consultation_type" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white" onchange="toggleConsultationTypeFields()">
                                <option value="presencial" {{ old('type', $consultation->type ?? '') === 'presencial' ? 'selected' : '' }}>🏥 Presencial (No Consultório)</option>
                                <option value="teleconsulta" {{ old('type', $consultation->type ?? '') === 'teleconsulta' ? 'selected' : '' }}>💻 Teleconsulta (Online)</option>
                                <option value="domicilio" {{ old('type', $consultation->type ?? '') === 'domicilio' ? 'selected' : '' }}>🏠 Domicílio (Em Casa)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Campos Dinâmicos: Teleconsulta -->
                    <div id="teleconsultationInfo" class="hidden mt-4 p-4 bg-indigo-50 border border-indigo-200 rounded-xl">
                        <p class="text-sm text-indigo-800 flex items-start gap-2">
                            <i class="fas fa-video mt-0.5"></i>
                            <span>Um link direto do Jitsi Meet será gerado. O paciente clica e entra <strong>sem necessidade de login</strong>.</span>
                        </p>
                    </div>

                    <!-- Campos Dinâmicos: Domicílio -->
                    <div id="domicilioInfo" class="hidden mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                        <label class="block text-sm font-semibold text-amber-800 mb-2">
                            <i class="fas fa-map-marker-alt mr-1"></i> Endereço Completo para Visita Domiciliária <span class="text-red-500">*</span>
                        </label>
                        <textarea name="home_visit_address" rows="2" 
                                  class="w-full px-4 py-3 border border-amber-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 bg-white"
                                  placeholder="Ex: Bairro da Polana, Rua dos Gladiolos, Nº 45, Casa com portão azul. Referência: Perto da farmácia X.">{{ old('home_visit_address', $consultation->home_visit_address ?? '') }}</textarea>
                    </div>
                </div>

                <hr class="border-gray-100">

                <!-- 3. MODALIDADE DE PAGAMENTO (Blocos Clicáveis) -->
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-credit-card text-purple-600"></i> Modalidade de Pagamento <span class="text-red-500">*</span>
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">Selecione como o paciente irá pagar. Preencha os detalhes para finalizar o agendamento.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        <!-- Bloco 1: Numerário -->
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="numerario" class="peer sr-only" onchange="togglePaymentFields('numerario')" {{ old('payment_method', $consultation->payment_method ?? '') === 'numerario' ? 'checked' : '' }}>
                            <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-purple-600 peer-checked:bg-purple-50 hover:bg-gray-50 transition h-full flex flex-col items-center text-center">
                                <i class="fas fa-money-bill-wave text-3xl text-green-600 mb-2"></i>
                                <span class="font-bold text-gray-800">Dinheiro (Numerário)</span>
                                <span class="text-xs text-gray-500 mt-1">Pagamento no local em notas</span>
                            </div>
                        </label>

                        <!-- Bloco 2: Mobile Money -->
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="mobile_money" class="peer sr-only" onchange="togglePaymentFields('mobile_money')" {{ old('payment_method', $consultation->payment_method ?? '') === 'mobile_money' ? 'checked' : '' }}>
                            <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-purple-600 peer-checked:bg-purple-50 hover:bg-gray-50 transition h-full flex flex-col items-center text-center">
                                <i class="fas fa-mobile-alt text-3xl text-orange-500 mb-2"></i>
                                <span class="font-bold text-gray-800">Carteiras Móveis</span>
                                <span class="text-xs text-gray-500 mt-1">M-Pesa, e-Mola, M-Kesh</span>
                            </div>
                        </label>

                        <!-- Bloco 3: POS / TPA -->
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="pos_tpa" class="peer sr-only" onchange="togglePaymentFields('pos_tpa')" {{ old('payment_method', $consultation->payment_method ?? '') === 'pos_tpa' ? 'checked' : '' }}>
                            <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-purple-600 peer-checked:bg-purple-50 hover:bg-gray-50 transition h-full flex flex-col items-center text-center">
                                <i class="fas fa-credit-card text-3xl text-blue-600 mb-2"></i>
                                <span class="font-bold text-gray-800">Cartões Bancários</span>
                                <span class="text-xs text-gray-500 mt-1">POS / TPA no consultório</span>
                            </div>
                        </label>

                        <!-- Bloco 4: Transferência / Instantâneo -->
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="transferencia" class="peer sr-only" onchange="togglePaymentFields('transferencia')" {{ old('payment_method', $consultation->payment_method ?? '') === 'transferencia' ? 'checked' : '' }}>
                            <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-purple-600 peer-checked:bg-purple-50 hover:bg-gray-50 transition h-full flex flex-col items-center text-center">
                                <i class="fas fa-university text-3xl text-indigo-600 mb-2"></i>
                                <span class="font-bold text-gray-800">Transferência / Instantâneo</span>
                                <span class="text-xs text-gray-500 mt-1">METIX, SPIM, IBAN</span>
                            </div>
                        </label>

                        <!-- Bloco 5: Seguros -->
                        <label class="cursor-pointer md:col-span-2 lg:col-span-2">
                            <input type="radio" name="payment_method" value="seguro" class="peer sr-only" onchange="togglePaymentFields('seguro')" {{ old('payment_method', $consultation->payment_method ?? '') === 'seguro' ? 'checked' : '' }}>
                            <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-purple-600 peer-checked:bg-purple-50 hover:bg-gray-50 transition h-full flex flex-col items-center text-center">
                                <i class="fas fa-shield-alt text-3xl text-purple-600 mb-2"></i>
                                <span class="font-bold text-gray-800">Seguros de Saúde e Convênios</span>
                                <span class="text-xs text-gray-500 mt-1">AMS, União, ENI, Hollard, etc.</span>
                            </div>
                        </label>
                    </div>

                    <!-- Campos Dinâmicos de Pagamento -->
                    <div id="payment_details_container" class="bg-gray-50 p-6 rounded-xl border border-gray-200 hidden">
                        <h4 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-purple-600"></i> Detalhes do Pagamento
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Campo de Valor (Sempre visível) -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Valor da Consulta (MT)</label>
                                <input type="number" name="total_amount" step="0.01" min="0" value="{{ old('total_amount', $consultation->total_amount ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="0.00">
                            </div>

                            <!-- Dinheiro -->
                            <div id="field_numerario" class="payment-field hidden md:col-span-2">
                                <p class="text-sm text-gray-600 flex items-center gap-2">
                                    <i class="fas fa-check-circle text-green-600"></i> O paciente pagará em dinheiro no momento da consulta.
                                </p>
                            </div>

                            <!-- Mobile Money -->
                            <div id="field_mobile_money" class="payment-field hidden">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Operadora</label>
                                <select name="payment_provider" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                                    <option value="M-Pesa">M-Pesa (Vodacom)</option>
                                    <option value="e-Mola">e-Mola (Movitel)</option>
                                    <option value="M-Kesh">M-Kesh (Tmcel)</option>
                                </select>
                            </div>
                            <div id="field_mobile_money_ref" class="payment-field hidden">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Número de Telemóvel / ID Transação</label>
                                <input type="text" name="payment_reference" value="{{ old('payment_reference', $consultation->payment_reference ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Ex: 841234567 ou ID123456">
                            </div>

                            <!-- POS / TPA -->
                            <div id="field_pos_tpa" class="payment-field hidden md:col-span-2">
                                <p class="text-sm text-gray-600 flex items-center gap-2">
                                    <i class="fas fa-check-circle text-blue-600"></i> O paciente passará o cartão no TPA no momento da consulta.
                                </p>
                            </div>

                            <!-- Transferência -->
                            <div id="field_transferencia" class="payment-field hidden">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Banco / Plataforma</label>
                                <select name="payment_provider" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                                    <option value="BCI">BCI</option>
                                    <option value="Millennium BIM">Millennium BIM</option>
                                    <option value="Standard Bank">Standard Bank</option>
                                    <option value="METIX">METIX</option>
                                    <option value="SPIM">SPIM</option>
                                    <option value="Outro">Outro</option>
                                </select>
                            </div>
                            <div id="field_transferencia_ref" class="payment-field hidden">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nº de Referência / Comprovativo</label>
                                <input type="text" name="payment_reference" value="{{ old('payment_reference', $consultation->payment_reference ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Ex: REF12345 ou Nº do Comprovativo">
                            </div>

                            <!-- Seguro -->
                            <div id="field_seguro" class="payment-field hidden">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Seguradora</label>
                                <select name="payment_provider" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                                    <option value="">Selecione a seguradora...</option>
                                    @foreach($insurances as $ins)
                                        <option value="{{ $ins->name }}" {{ old('payment_provider', $consultation->payment_provider ?? '') == $ins->name ? 'selected' : '' }}>
                                            {{ $ins->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="field_seguro_ref" class="payment-field hidden">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nº de Apólice / Cartão</label>
                                <input type="text" name="payment_reference" value="{{ old('payment_reference', $consultation->payment_reference ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Ex: 123456789">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                <!-- 4. OBSERVAÇÕES -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-purple-600 mr-1"></i> Observações / Motivo da Consulta
                    </label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Ex: Primeira consulta de rotina, dor abdominal...">{{ old('notes', $consultation->notes ?? '') }}</textarea>
                </div>

                <!-- BOTÕES DE AÇÃO -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                    <button type="submit" class="flex-1 py-3 px-4 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl transition flex items-center justify-center gap-2 shadow-md transform hover:scale-[1.02]">
                        <i class="fas fa-calendar-check"></i> Confirmar e Agendar Consulta
                    </button>
                    <a href="{{ route('consultations.index') }}" class="flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // 1. Filtrar médicos por especialidade
        function filterDoctorsBySpecialty() {
            const specialtyId = document.getElementById('specialty_id').value;
            const doctorSelect = document.getElementById('doctor_id');
            const options = doctorSelect.querySelectorAll('option');
            
            doctorSelect.innerHTML = '<option value="">Selecione um médico...</option>';
            
            options.forEach(option => {
                if (option.value === "") return;
                const doctorSpecialty = option.getAttribute('data-specialty');
                if (!specialtyId || doctorSpecialty == specialtyId || doctorSpecialty === "null" || doctorSpecialty === "") {
                    doctorSelect.appendChild(option.cloneNode(true));
                }
            });

            if (doctorSelect.options.length <= 1) {
                doctorSelect.innerHTML = '<option value="">Nenhum médico disponível para esta especialidade</option>';
            }
        }

        // 2. Mostrar campos de Teleconsulta ou Domicílio
        function toggleConsultationTypeFields() {
            const type = document.getElementById('consultation_type').value;
            const teleInfo = document.getElementById('teleconsultationInfo');
            const domicilioInfo = document.getElementById('domicilioInfo');
            const addressField = document.querySelector('textarea[name="home_visit_address"]');

            teleInfo.classList.add('hidden');
            domicilioInfo.classList.add('hidden');
            addressField.removeAttribute('required');

            if (type === 'teleconsulta') {
                teleInfo.classList.remove('hidden');
            } else if (type === 'domicilio') {
                domicilioInfo.classList.remove('hidden');
                addressField.setAttribute('required', 'required');
            }
        }

        // 3. Mostrar campos inteligentes de Pagamento
        function togglePaymentFields(method) {
            // Esconder todos os campos de pagamento
            document.getElementById('payment_details_container').classList.remove('hidden');
            document.querySelectorAll('.payment-field').forEach(el => el.classList.add('hidden'));
            
            // Remover 'required' de todos os campos de referência
            document.querySelectorAll('.payment-field input, .payment-field select').forEach(el => {
                el.removeAttribute('required');
            });

            // Mostrar os campos específicos e torná-los required se necessário
            if (method === 'numerario') {
                document.getElementById('field_numerario').classList.remove('hidden');
            } else if (method === 'mobile_money') {
                document.getElementById('field_mobile_money').classList.remove('hidden');
                document.getElementById('field_mobile_money_ref').classList.remove('hidden');
                document.querySelector('#field_mobile_money_ref input').setAttribute('required', 'required');
            } else if (method === 'pos_tpa') {
                document.getElementById('field_pos_tpa').classList.remove('hidden');
            } else if (method === 'transferencia') {
                document.getElementById('field_transferencia').classList.remove('hidden');
                document.getElementById('field_transferencia_ref').classList.remove('hidden');
                document.querySelector('#field_transferencia_ref input').setAttribute('required', 'required');
            } else if (method === 'seguro') {
                document.getElementById('field_seguro').classList.remove('hidden');
                document.getElementById('field_seguro_ref').classList.remove('hidden');
                document.querySelector('#field_seguro_ref input').setAttribute('required', 'required');
            }
        }

        // Inicializar ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            filterDoctorsBySpecialty();
            toggleConsultationTypeFields();
            
            // Inicializar campos de pagamento se já houver um método selecionado (edição)
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
            if (selectedPayment) {
                togglePaymentFields(selectedPayment.value);
            }
        });
    </script>

</x-layouts.admin>