<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Senha • Portal do Paciente - Makombe</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-emerald-500 to-teal-600 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            
            <div class="bg-gradient-to-br from-emerald-600 via-teal-700 to-cyan-700 px-8 py-6 text-center text-white">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-lock text-3xl"></i>
                </div>
                <h1 class="text-2xl font-black">Criar Nova Senha</h1>
                <p class="text-emerald-100 text-sm mt-1">Digite o PIN recebido e crie uma nova senha</p>
            </div>

            <div class="p-6 lg:p-8">
                @if(session('pin'))
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl">
                        <p class="text-sm text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            <strong>PIN gerado:</strong> <span class="font-mono font-bold">{{ session('pin') }}</span>
                        </p>
                        @if(session('whatsapp_sent'))
                            <p class="text-xs text-green-700 mt-1">
                                <i class="fab fa-whatsapp mr-1"></i> PIN também enviado por WhatsApp!
                            </p>
                        @endif
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

                <form method="POST" action="{{ route('patient.password.reset') }}" class="space-y-4">
                    @csrf

                    <input type="hidden" name="pin" value="{{ $pin }}">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-phone text-emerald-600 mr-1"></i> Telemóvel
                        </label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm">+258</span>
                            <input type="tel" name="phone" required maxlength="9"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-r-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                   placeholder="841234567">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-key text-emerald-600 mr-1"></i> PIN de Recuperação
                        </label>
                        <input type="text" name="pin_display" value="{{ $pin }}" readonly
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 font-mono font-bold text-center text-lg">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i> PIN recebido por WhatsApp
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-lock text-emerald-600 mr-1"></i> Nova Senha
                        </label>
                        <input type="password" name="new_password" required minlength="6"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="Mínimo 6 caracteres">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-lock text-emerald-600 mr-1"></i> Confirmar Nova Senha
                        </label>
                        <input type="password" name="new_password_confirmation" required minlength="6"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="Repita a senha">
                    </div>

                    <button type="submit" 
                            class="w-full py-3 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold rounded-xl shadow-lg transition">
                        <i class="fas fa-save mr-2"></i>CRIAR NOVA SENHA
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>