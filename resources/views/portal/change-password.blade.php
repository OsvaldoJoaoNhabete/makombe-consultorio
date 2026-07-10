<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Senha Personalizada • Portal do Paciente - Makombe</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Lato', sans-serif; }
        .strength-bar { transition: width 0.3s ease, background-color 0.3s ease; }
    </style>
</head>
<body class="bg-gradient-to-br from-emerald-500 to-teal-600 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            
            <div class="bg-gradient-to-br from-emerald-600 via-teal-700 to-cyan-700 px-8 py-6 text-center text-white">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-shield-alt text-3xl"></i>
                </div>
                <h1 class="text-2xl font-black">Criar Senha Personalizada</h1>
                <p class="text-emerald-100 text-sm mt-1">Primeiro acesso - Crie sua senha segura</p>
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

                <!-- Aviso -->
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-xl">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-1"></i>
                        Olá <strong>{{ explode(' ', $patient->full_name)[0] }}</strong>, 
                        crie uma senha personalizada para substituir a temporária.
                    </p>
                </div>

                <form method="POST" action="{{ route('patient.password.change.post') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-key text-emerald-600 mr-1"></i> Senha Atual (temporária)
                        </label>
                        <div class="relative">
                            <input type="password" name="current_password" id="currentPassword" required
                                   class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Digite a senha que recebeu">
                            <button type="button" onclick="togglePassword('currentPassword', 'eye1')" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-emerald-600">
                                <i class="fas fa-eye" id="eye1"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-lock text-emerald-600 mr-1"></i> Nova Senha
                        </label>
                        <div class="relative">
                            <input type="password" name="new_password" id="newPassword" required minlength="6"
                                   onkeyup="checkPasswordStrength(this.value)"
                                   class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Mínimo 6 caracteres">
                            <button type="button" onclick="togglePassword('newPassword', 'eye2')" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-emerald-600">
                                <i class="fas fa-eye" id="eye2"></i>
                            </button>
                        </div>
                        <!-- Indicador de força -->
                        <div class="mt-2 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                            <div id="strengthBar" class="strength-bar h-full w-0 bg-gray-300"></div>
                        </div>
                        <p id="strengthText" class="text-xs text-gray-500 mt-1">Digite uma senha</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-lock text-emerald-600 mr-1"></i> Confirmar Nova Senha
                        </label>
                        <div class="relative">
                            <input type="password" name="new_password_confirmation" id="confirmPassword" required minlength="6"
                                   onkeyup="checkPasswordMatch()"
                                   class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Repita a nova senha">
                            <button type="button" onclick="togglePassword('confirmPassword', 'eye3')" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-emerald-600">
                                <i class="fas fa-eye" id="eye3"></i>
                            </button>
                        </div>
                        <p id="matchText" class="text-xs text-gray-500 mt-1"></p>
                    </div>

                    <button type="submit" 
                            class="w-full py-3 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold rounded-xl shadow-lg transition">
                        <i class="fas fa-save mr-2"></i> CRIAR MINHA SENHA
                    </button>
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

        function checkPasswordStrength(password) {
            const bar = document.getElementById('strengthBar');
            const text = document.getElementById('strengthText');
            
            if (password.length === 0) {
                bar.style.width = '0%';
                text.textContent = 'Digite uma senha';
                text.className = 'text-xs text-gray-500 mt-1';
                return;
            }
            
            let strength = 0;
            if (password.length >= 6) strength += 25;
            if (password.length >= 10) strength += 15;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 20;
            if (/[0-9]/.test(password)) strength += 20;
            if (/[^a-zA-Z0-9]/.test(password)) strength += 20;
            
            let message, color;
            if (strength < 30) { message = 'Fraca'; color = 'bg-red-500'; }
            else if (strength < 60) { message = 'Média'; color = 'bg-yellow-500'; }
            else if (strength < 80) { message = 'Boa'; color = 'bg-blue-500'; }
            else { message = 'Forte'; color = 'bg-green-500'; }
            
            bar.style.width = strength + '%';
            bar.className = 'strength-bar h-full ' + color;
            text.textContent = message;
            text.className = 'text-xs mt-1 ' + (strength >= 60 ? 'text-green-600' : 'text-orange-600');
            
            checkPasswordMatch();
        }

        function checkPasswordMatch() {
            const newPass = document.getElementById('newPassword').value;
            const confirm = document.getElementById('confirmPassword').value;
            const matchText = document.getElementById('matchText');
            
            if (confirm.length === 0) {
                matchText.textContent = '';
                return;
            }
            
            if (newPass === confirm) {
                matchText.innerHTML = '<i class="fas fa-check-circle text-green-600"></i> As senhas coincidem';
                matchText.className = 'text-xs text-green-600 mt-1';
            } else {
                matchText.innerHTML = '<i class="fas fa-times-circle text-red-600"></i> As senhas não coincidem';
                matchText.className = 'text-xs text-red-600 mt-1';
            }
        }
    </script>
</body>
</html>