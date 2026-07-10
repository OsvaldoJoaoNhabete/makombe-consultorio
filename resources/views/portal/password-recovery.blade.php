<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha • Portal do Paciente - Makombe</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-emerald-500 to-teal-600 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            
            <div class="bg-gradient-to-br from-emerald-600 via-teal-700 to-cyan-700 px-8 py-6 text-center text-white">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-key text-3xl"></i>
                </div>
                <h1 class="text-2xl font-black">Recuperar Senha</h1>
                <p class="text-emerald-100 text-sm mt-1">Informe seus dados para receber um PIN</p>
            </div>

            <div class="p-6 lg:p-8">
                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm">
                        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <ul class="text-sm text-red-800 space-y-1">
                            @foreach($errors->all() as $error)
                                <li><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('patient.password.recover') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-phone text-emerald-600 mr-1"></i> Telemóvel
                        </label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm">+258</span>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required maxlength="9"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-r-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                   placeholder="841234567">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-id-card text-emerald-600 mr-1"></i> Número do BI
                        </label>
                        <input type="text" name="bi_number" value="{{ old('bi_number') }}" required maxlength="13"
                               oninput="this.value = this.value.toUpperCase().replace(/[^0-9A-Z]/g, '')"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 uppercase"
                               placeholder="123456789012A">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-birthday-cake text-emerald-600 mr-1"></i> Data de Nascimento
                        </label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <button type="submit" 
                            class="w-full py-3 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold rounded-xl shadow-lg transition">
                        <i class="fas fa-paper-plane mr-2"></i>ENVIAR PIN
                    </button>

                    <div class="text-center text-sm text-gray-600">
                        <a href="{{ route('patient.login') }}" class="text-emerald-600 hover:text-emerald-800 font-semibold">
                            <i class="fas fa-arrow-left mr-1"></i> Voltar ao Login
                        </a>
                    </div>
                </form>

                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <p class="text-xs text-blue-800">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>Como funciona?</strong><br>
                        1. Informe seu telefone, BI e data de nascimento<br>
                        2. Receberá um PIN de 6 dígitos por WhatsApp<br>
                        3. Use o PIN para criar uma nova senha<br>
                        4. O PIN expira em 24 horas
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>