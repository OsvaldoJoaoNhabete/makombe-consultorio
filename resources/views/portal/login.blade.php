<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login • Portal do Paciente - Makombe</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Lato', sans-serif; }
        .bg-violet-gradient { background: linear-gradient(135deg, #4c1d95 0%, #5b21b6 50%, #6d28d9 100%); }
    </style>
</head>
<body class="bg-violet-gradient min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            
            <!-- Header com Logótipo -->
            <div class="bg-violet-gradient px-8 py-10 text-center">
                <div class="w-24 h-24 bg-white rounded-2xl flex items-center justify-center shadow-xl mx-auto mb-4 p-2">
                    @if(file_exists(public_path('images/logo-mcm.png')))
                        <img src="{{ asset('images/logo-mcm.png') }}" alt="Makombe" class="w-full h-full object-contain">
                    @else
                        <i class="fas fa-heartbeat text-4xl text-violet-700"></i>
                    @endif
                </div>
                <h1 class="text-3xl font-black text-white mb-1">MAKOMBE</h1>
                <p class="text-violet-200 text-sm">Portal do Paciente</p>
                <p class="text-violet-300 text-xs italic mt-1">"Aqui você tem saúde"</p>
            </div>

            <!-- Form -->
            <div class="p-8">
                <!-- Botão Voltar à Página Inicial -->
                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-violet-600 hover:text-violet-800 transition group mb-6">
                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> 
                    Voltar à Página Inicial
                </a>

                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Bem-vindo! 👋</h2>

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('patient.login.post') }}" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-violet-600 mr-2"></i>Email ou Telemóvel
                        </label>
                        <input type="text" name="identifier" value="{{ old('identifier') }}" required autofocus
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500"
                               placeholder="paciente@email.com ou 841234567">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock text-violet-600 mr-2"></i>Senha
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="patientPassword" required
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500"
                                   placeholder="••••••••">
                            <button type="button" onclick="togglePassword('patientPassword', 'patientEyeIcon')" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-violet-600 transition">
                                <i id="patientEyeIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-violet-600 rounded focus:ring-violet-500">
                            <span class="text-gray-600">Lembrar-me</span>
                        </label>
                        <a href="{{ route('patient.password.recovery') }}" class="text-violet-600 hover:text-violet-800 font-medium">
                            Esqueci a senha
                        </a>
                    </div>

                    <button type="submit" 
                            class="w-full py-3 px-4 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl shadow-lg transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>ENTRAR
                    </button>
                </form>

                <div class="mt-6 space-y-3 text-center">
                    <a href="{{ route('patient.register') }}" class="block w-full py-3 px-4 border-2 border-violet-600 text-violet-600 hover:bg-violet-50 font-semibold rounded-xl transition">
                        <i class="fas fa-user-plus mr-2"></i>Criar Conta de Paciente
                    </a>
                    <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-user-shield mr-1"></i> Acesso Staff/Admin
                    </a>
                </div>
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
    </script>
</body>
</html>