<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registo - Makombe Consultório Médico</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .error-message { animation: shake 0.3s ease-in-out; }
        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-8">
        
        <!-- Apenas o link Voltar à Home -->
        <div class="mb-4">
            <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-800 font-medium text-sm">
                <i class="fas fa-arrow-left"></i> Voltar à página inicial
            </a>
        </div>

        <div class="text-center mb-6">
            <img src="{{ asset('images/logo-mcm.png') }}" alt="Makombe" class="w-20 h-20 mx-auto mb-3 object-contain">
            <h1 class="text-2xl font-bold text-gray-800 mb-1">MAKOMBE</h1>
            <p class="text-gray-600 text-sm">Consultório Médico</p>
            <p class="text-purple-600 text-sm italic mt-1">"Aqui você tem saúde"</p>
            <p class="text-gray-500 text-xs mt-2">Criar Nova Conta de Paciente</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 rounded-r-lg error-message">
                <ul class="text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}" class="space-y-4" id="registerForm" novalidate>
            @csrf
            <input type="hidden" name="type" value="patient">
            
            <div class="bg-purple-50 border border-purple-200 rounded-xl p-3">
                <p class="text-sm text-purple-800 flex items-center gap-2"><i class="fas fa-user"></i><strong>Registo de Paciente</strong></p>
                <p class="text-xs text-purple-600 mt-1">Profissionais de saúde são registados pela administração do consultório.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nome Completo <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required minlength="3" maxlength="255"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none transition" placeholder="Ex: João da Silva">
                <p id="nameError" class="mt-1 text-xs text-red-600 hidden error-message"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Telemóvel <span class="text-red-500">*</span></label>
                <div class="flex">
                    <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm font-medium">+258</span>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required minlength="9" maxlength="9" pattern="[0-9]{9}"
                           class="flex-1 px-4 py-3 border border-gray-300 rounded-r-xl focus:ring-2 focus:ring-purple-500 outline-none transition" placeholder="841234567">
                </div>
                <p id="phoneError" class="mt-1 text-xs text-red-600 hidden error-message"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-gray-400 text-xs font-normal">(Opcional)</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none transition" placeholder="seu@email.com">
                <p id="emailError" class="mt-1 text-xs text-red-600 hidden error-message"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">PIN de Acesso <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" name="password" id="password" required minlength="4" maxlength="4" pattern="[0-9]{4}" inputmode="numeric" autocomplete="new-password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none transition tracking-widest" placeholder="••••" maxlength="4">
                    <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-purple-600">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
                <p class="text-xs text-purple-600 mt-1"><i class="fas fa-info-circle"></i> Use 4 dígitos numéricos (ex: 1234). Guarde bem o seu PIN!</p>
                <p id="passwordError" class="mt-1 text-xs text-red-600 hidden error-message"></p>
            </div>

            <div class="flex items-start gap-2">
                <input type="checkbox" name="terms" id="terms" required class="mt-1 rounded text-purple-600 focus:ring-purple-500">
                <label for="terms" class="text-sm text-gray-600">
                    Concordo com os <a href="{{ route('terms') }}" target="_blank" class="text-purple-600 hover:underline">Termos e Condições</a> 
                    e <a href="{{ route('privacy') }}" target="_blank" class="text-purple-600 hover:underline">Política de Privacidade</a> <span class="text-red-500">*</span>
                </label>
            </div>
            <p id="termsError" class="text-xs text-red-600 hidden error-message"></p>

            <button type="submit" id="submitBtn" class="w-full py-3 px-4 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg transition transform hover:scale-[1.02] flex items-center justify-center gap-2 disabled:bg-gray-400 disabled:cursor-not-allowed">
                <i class="fas fa-user-plus"></i> Criar Conta
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500 border-t border-gray-200 pt-4">
            Já tem conta? <a href="{{ route('login') }}" class="text-purple-600 font-bold hover:underline">Faça login</a>
        </div>
        
        <div class="mt-4 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} <span class="text-purple-600 font-semibold">Makombe Consultório Médico</span>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        document.getElementById('phone').addEventListener('input', function(e) { this.value = this.value.replace(/[^0-9]/g, ''); });
        document.getElementById('password').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 4) this.value = this.value.slice(0, 4);
        });

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let isValid = true;
            document.querySelectorAll('[id$="Error"]').forEach(el => el.classList.add('hidden'));
            
            const name = document.getElementById('name').value.trim();
            if (name.length < 3) {
                document.getElementById('nameError').textContent = 'O nome deve ter pelo menos 3 caracteres.';
                document.getElementById('nameError').classList.remove('hidden');
                isValid = false;
            }
            
            const phone = document.getElementById('phone').value.trim();
            if (!/^[0-9]{9}$/.test(phone)) {
                document.getElementById('phoneError').textContent = 'O telemóvel deve ter exatamente 9 dígitos numéricos.';
                document.getElementById('phoneError').classList.remove('hidden');
                isValid = false;
            }
            
            const email = document.getElementById('email').value.trim();
            if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                document.getElementById('emailError').textContent = 'Insira um email válido.';
                document.getElementById('emailError').classList.remove('hidden');
                isValid = false;
            }
            
            const password = document.getElementById('password').value;
            if (!/^[0-9]{4}$/.test(password)) {
                document.getElementById('passwordError').textContent = 'O PIN deve ter exatamente 4 dígitos numéricos.';
                document.getElementById('passwordError').classList.remove('hidden');
                isValid = false;
            }
            
            if (!document.getElementById('terms').checked) {
                document.getElementById('termsError').textContent = 'Deve aceitar os Termos e Condições para continuar.';
                document.getElementById('termsError').classList.remove('hidden');
                isValid = false;
            }
            
            if (isValid) {
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> A criar conta...';
                this.submit();
            }
        });
    </script>
</body>
</html>