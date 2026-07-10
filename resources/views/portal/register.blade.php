<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta • Portal do Paciente - Makombe</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Lato', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #10b981 0%, #0d9488 50%, #0891b2 100%); }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-2xl">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            
            <div class="bg-gradient-to-br from-emerald-600 via-teal-700 to-cyan-700 px-8 py-6 text-center text-white">
                <h1 class="text-2xl font-black">Criar Conta de Paciente</h1>
                <p class="text-emerald-100 text-sm mt-1">Preencha os dados abaixo</p>
            </div>

            <div class="p-6 lg:p-8">
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
                               pattern="[a-zA-ZÀ-ÿ\s\-']+"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="Ex: João da Silva">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Apenas letras, espaços e hífens. Máximo 50 caracteres.
                        </p>
                    </div>

                    <!-- BI -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Número do BI <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="bi_number" id="biInput" value="{{ old('bi_number') }}" required maxlength="13"
                               pattern="[0-9]{12}[A-Za-z]"
                               oninput="formatBI(this)"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 uppercase"
                               placeholder="Ex: 123456789012A">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            12 números + 1 letra (ex: 123456789012A). A letra será automaticamente maiúscula.
                        </p>
                    </div>

                    <!-- Data Nascimento e Género -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Data de Nascimento <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}" required max="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Género <span class="text-red-500">*</span>
                            </label>
                            <select name="gender" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
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
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-r-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                   placeholder="841234567">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Apenas números. Deve começar com 8 ou 9.
                        </p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="paciente@email.com">
                    </div>

                    <!-- Endereço -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Endereço
                        </label>
                        <textarea name="address" rows="2" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                  placeholder="Bairro, Rua, Número">{{ old('address') }}</textarea>
                    </div>

                    <!-- Aviso sobre senha automática -->
                    <div class="bg-amber-50 border-2 border-amber-300 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-key text-amber-600 text-xl mt-1"></i>
                            <div>
                                <h4 class="font-bold text-amber-900 mb-1">🔐 Senha Gerada Automaticamente</h4>
                                <p class="text-sm text-amber-800">
                                    O sistema gerará uma <strong>senha temporária de 6 dígitos</strong> que será exibida após o registo. 
                                    <strong>Guarde esta senha com cuidado!</strong> Você poderá alterá-la após o primeiro login.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Termos -->
                    <div class="flex items-start gap-2 p-3 bg-gray-50 rounded-lg">
                        <input type="checkbox" name="terms" required class="w-4 h-4 text-emerald-600 rounded mt-0.5">
                        <label class="text-xs text-gray-700">
                            Li e concordo com os 
                            <a href="{{ route('patient.terms') }}" target="_blank" class="text-emerald-600 hover:underline font-semibold">Termos e Condições</a> 
                            e a 
                            <a href="{{ route('patient.privacy') }}" target="_blank" class="text-emerald-600 hover:underline font-semibold">Política de Privacidade</a>.
                        </label>
                    </div>

                    <!-- Botões -->
                    <button type="submit" 
                            class="w-full py-3 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold rounded-xl shadow-lg transition">
                        <i class="fas fa-user-plus mr-2"></i>CRIAR CONTA
                    </button>

                    <div class="text-center text-sm text-gray-600">
                        Já tem conta? 
                        <a href="{{ route('patient.login') }}" class="text-emerald-600 hover:text-emerald-800 font-semibold">Fazer Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Formatar BI automaticamente
        function formatBI(input) {
            // Remover tudo que não é número ou letra
            let value = input.value.replace(/[^0-9A-Za-z]/g, '');
            
            // Limitar a 13 caracteres (12 números + 1 letra)
            if (value.length > 13) {
                value = value.substring(0, 13);
            }
            
            // Converter a última letra para maiúscula
            if (value.length === 13) {
                value = value.substring(0, 12) + value.charAt(12).toUpperCase();
            }
            
            input.value = value;
        }
    </script>

</body>
</html>