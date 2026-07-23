<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Primeiro Acesso - Makombe Consultório Médico</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        
        <!-- Cabeçalho com Branding -->
        <div class="bg-gradient-to-r from-purple-700 to-indigo-700 p-8 text-center">
            <div class="w-16 h-16 bg-white rounded-full mx-auto flex items-center justify-center mb-4 shadow-lg">
                <span class="text-3xl font-bold text-purple-700">M</span>
            </div>
            <h1 class="text-2xl font-bold text-white mb-1">Makombe</h1>
            <p class="text-purple-200 text-sm italic">Consultório Médico</p>
        </div>
        
        <!-- Corpo do Formulário -->
        <div class="p-8">
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-2">Primeiro Acesso</h2>
                <p class="text-gray-600 text-sm">Por segurança, deve definir uma nova palavra-passe antes de aceder ao sistema.</p>
            </div>

            @if (session('warning'))
                <div class="mb-6 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-lg shadow-sm">
                    <p class="text-sm text-amber-700 font-medium">
                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('warning') }}
                    </p>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                    <p class="text-sm text-green-700 font-medium">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </p>
                </div>
            @endif

            <form method="POST" action="{{ route('first-login.update') }}" id="passwordForm">
                @csrf
                
                <!-- Nova Palavra-passe -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nova Palavra-passe <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition" 
                               placeholder="Mínimo 8 caracteres" required>
                        <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-purple-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password') 
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> 
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Deve conter: 8+ caracteres, 1 maiúscula, 1 minúscula e 1 número</p>
                </div>

                <!-- Confirmar Palavra-passe -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmar Palavra-passe <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition" 
                               placeholder="Repita a nova palavra-passe" required>
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-purple-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Botão de Submissão -->
                <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg shadow-md transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <i class="fas fa-lock-open"></i> Alterar e Aceder ao Sistema
                </button>
            </form>

            <!-- Link de Logout -->
            <div class="mt-6 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-red-600 transition-colors flex items-center justify-center gap-1 mx-auto">
                        <i class="fas fa-sign-out-alt"></i> Terminar Sessão
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts de Interatividade -->
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const p1 = document.getElementById('password').value;
            const p2 = document.getElementById('password_confirmation').value;
            
            if (p1 !== p2) {
                e.preventDefault();
                alert('As palavras-passe não coincidem!');
                return false;
            }
            if (p1.length < 8) {
                e.preventDefault();
                alert('A palavra-passe deve ter pelo menos 8 caracteres!');
                return false;
            }
            
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/;
            if (!passwordRegex.test(p1)) {
                e.preventDefault();
                alert('A palavra-passe deve conter pelo menos uma letra maiúscula, uma minúscula e um número!');
                return false;
            }
        });
    </script>
</body>
</html>