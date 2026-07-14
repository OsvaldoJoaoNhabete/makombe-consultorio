<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Criar Conta • Portal do Paciente - Makombe</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Lato', sans-serif; }
        .bg-violet-gradient { background: linear-gradient(135deg, #4c1d95 0%, #5b21b6 50%, #6d28d9 100%); }
    </style>
</head>
<body class="bg-violet-gradient min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-2xl">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            
            <!-- Header com Logótipo -->
            <div class="bg-violet-gradient px-8 py-6 text-center text-white">
                <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center shadow-xl mx-auto mb-3 p-2">
                    @if(file_exists(public_path('images/logo-mcm.png')))
                        <img src="{{ asset('images/logo-mcm.png') }}" alt="Makombe" class="w-full h-full object-contain">
                    @else
                        <i class="fas fa-heartbeat text-3xl text-violet-700"></i>
                    @endif
                </div>
                <h1 class="text-2xl font-black">Criar Conta de Paciente</h1>
                <p class="text-violet-200 text-sm mt-1">Preencha os dados abaixo</p>
            </div>

            <div class="p-6 lg:p-8">
                <!-- Botão Voltar à Página Inicial -->
                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-violet-600 hover:text-violet-800 transition group mb-4">
                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> 
                    Voltar à Página Inicial
                </a>

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <ul class="text-sm text-red-800 space-y-1">
                            @foreach($errors->all() as $error)
                                <li><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('patient.register.post') }}" class="space-y-4">
                    @csrf

                    <!-- Nome Completo -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Nome Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required maxlength="50"
                               pattern="^[a-zA-ZÀ-ÿ\s\-']+$"
                               oninput="this.value = this.value.replace(/[^a-zA-ZÀ-ÿ\s\-']/g, '')"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500"
                               placeholder="Ex: João da Silva">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i> Apenas letras, espaços e hífens. Números não são permitidos.
                        </p>
                    </div>

                    <!-- BI -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Número do BI <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="bi_number" id="biInput" value="{{ old('bi_number') }}" required maxlength="13"
                               pattern="[0-9]{12}[A-Za-z]"
                               oninput="this.value = this.value.toUpperCase().replace(/[^0-9A-Z]/g, '')"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500 uppercase"
                               placeholder="Ex: 123456789012A">
                    </div>

                    <!-- Data Nascimento e Género -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Data de Nascimento <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required max="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500">
                            <p id="age_display" class="text-sm text-violet-600 mt-1 font-semibold hidden"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Género <span class="text-red-500">*</span>
                            </label>
                            <select name="gender" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500">
                                <option value="">Selecione...</option>
                                <option value="masculino" {{ old('gender') === 'masculino' ? 'selected' : '' }}>♂ Masculino</option>
                                <option value="feminino" {{ old('gender') === 'feminino' ? 'selected' : '' }}>♀ Feminino</option>
                            </select>
                        </div>
                    </div>

                    <!-- Telefone -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Telemóvel <span class="text-red-500">*</span>
                        </label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm">+258</span>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required maxlength="9"
                                   pattern="[89][0-9]{8}"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-r-xl focus:outline-none focus:ring-2 focus:ring-violet-500"
                                   placeholder="841234567">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500"
                               placeholder="paciente@email.com">
                    </div>

                    <!-- Endereço -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Endereço</label>
                        <textarea name="address" rows="2" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500"
                                  placeholder="Bairro, Rua, Número">{{ old('address') }}</textarea>
                    </div>

                    <!-- Senhas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Senha <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="regPassword" required minlength="6"
                                       class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500"
                                       placeholder="Mín. 6 caracteres">
                                <button type="button" onclick="togglePassword('regPassword', 'regEye1')" 
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-violet-600 transition">
                                    <i id="regEye1" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Confirmar Senha <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="regPasswordConfirm" required minlength="6"
                                       class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500"
                                       placeholder="Repita a senha">
                                <button type="button" onclick="togglePassword('regPasswordConfirm', 'regEye2')" 
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-violet-600 transition">
                                    <i id="regEye2" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Termos -->
                    <div class="flex items-start gap-2 p-3 bg-violet-50 rounded-lg border border-violet-100">
                        <input type="checkbox" name="terms" required class="w-4 h-4 text-violet-600 rounded mt-0.5 focus:ring-violet-500">
                        <label class="text-xs text-gray-700">
                            Li e concordo com os 
                            <a href="{{ route('patient.terms') }}" target="_blank" class="text-violet-600 hover:underline font-semibold">Termos e Condições</a> 
                            e a 
                            <a href="{{ route('patient.privacy') }}" target="_blank" class="text-violet-600 hover:underline font-semibold">Política de Privacidade</a>.
                        </label>
                    </div>

                    <!-- Botão -->
                    <button type="submit" 
                            class="w-full py-3 px-4 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl shadow-lg transition">
                        <i class="fas fa-user-plus mr-2"></i>CRIAR CONTA
                    </button>

                    <div class="text-center text-sm text-gray-600">
                        Já tem conta? 
                        <a href="{{ route('patient.login') }}" class="text-violet-600 hover:text-violet-800 font-semibold">Fazer Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.getElementById('birth_date').addEventListener('change', function() {
            const birthDate = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            const ageDisplay = document.getElementById('age_display');
            if (age >= 0 && age <= 120) {
                ageDisplay.textContent = `Idade calculada: ${age} anos`;
                ageDisplay.classList.remove('hidden', 'text-red-600');
                ageDisplay.classList.add('text-violet-600');
            } else if (age > 120) {
                ageDisplay.textContent = "Data de nascimento inválida";
                ageDisplay.classList.remove('hidden', 'text-violet-600');
                ageDisplay.classList.add('text-red-600');
            } else {
                ageDisplay.classList.add('hidden');
            }
        });
    </script>
</body>
</html>