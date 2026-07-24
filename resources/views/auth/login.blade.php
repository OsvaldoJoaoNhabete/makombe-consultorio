<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Makombe Consultório Médico</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .glass-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="glass-panel w-full max-w-md rounded-2xl shadow-2xl p-8">
        
        <div class="mb-4">
            <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-800 font-medium text-sm">
                <i class="fas fa-arrow-left"></i> Voltar à página inicial
            </a>
        </div>

        <div class="text-center mb-8">
            <img src="{{ asset('images/logo-mcm.png') }}" alt="Makombe" class="w-24 h-24 mx-auto mb-4 object-contain">
            <h1 class="text-3xl font-bold text-gray-800 mb-1">MAKOMBE</h1>
            <p class="text-gray-600 text-sm">Consultório Médico</p>
            <p class="text-purple-600 text-sm italic mt-1">"Aqui você tem saúde"</p>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
                <p class="text-sm text-green-700 font-medium"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                <ul class="text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-5" id="loginForm">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email ou Telemóvel <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fas fa-user text-gray-400"></i></div>
                    <input type="text" name="identifier" id="identifier" value="{{ old('identifier') }}" required
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition"
                           placeholder="ex: email@makombe.com ou 841234567">
                </div>
                <p id="identifierError" class="mt-1 text-xs text-red-600 hidden"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Senha ou PIN <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fas fa-lock text-gray-400"></i></div>
                    <input type="password" name="password" id="password" required minlength="4"
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition"
                           placeholder="••••••••">
                </div>
                <p id="passwordError" class="mt-1 text-xs text-red-600 hidden"></p>
                
                <!-- Link Esqueci minha senha -->
                <div class="mt-2 text-right">
                    <a href="{{ route('patient.password.recovery') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                        Esqueci minha senha
                    </a>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded text-purple-600 focus:ring-purple-500">
                    <span class="text-gray-600">Lembrar-me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg transition transform hover:scale-[1.02] flex items-center justify-center gap-2">
                <i class="fas fa-sign-in-alt"></i> Entrar
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500 border-t border-gray-200 pt-4">
            <p>Não tem conta? <a href="{{ route('register') }}" class="text-purple-600 font-bold hover:underline">Registe-se aqui</a></p>
        </div>
        
        <div class="mt-4 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} <span class="text-purple-600 font-semibold">Makombe Consultório Médico</span>. Todos os direitos reservados.
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            let isValid = true;
            const identifier = document.getElementById('identifier').value.trim();
            const password = document.getElementById('password').value;
            document.getElementById('identifierError').classList.add('hidden');
            document.getElementById('passwordError').classList.add('hidden');
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phoneRegex = /^[0-9]{9}$/;
            
            if (!identifier) {
                document.getElementById('identifierError').textContent = 'O email ou telemóvel é obrigatório.';
                document.getElementById('identifierError').classList.remove('hidden');
                isValid = false;
            } else if (!emailRegex.test(identifier) && !phoneRegex.test(identifier)) {
                document.getElementById('identifierError').textContent = 'Insira um email válido ou telemóvel com 9 dígitos.';
                document.getElementById('identifierError').classList.remove('hidden');
                isValid = false;
            }
            
            if (!password || password.length < 4) {
                document.getElementById('passwordError').textContent = 'A senha ou PIN deve ter pelo menos 4 caracteres.';
                document.getElementById('passwordError').classList.remove('hidden');
                isValid = false;
            }
            
            if (!isValid) e.preventDefault();
        });
    </script>
</body>
</html>