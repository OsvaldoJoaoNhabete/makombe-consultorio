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
        .gradient-bg { background: linear-gradient(135deg, #10b981 0%, #0d9488 50%, #0891b2 100%); }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            
            <!-- Header -->
            <div class="bg-gradient-to-br from-emerald-600 via-teal-700 to-cyan-700 px-8 py-10 text-center">
                <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center shadow-xl mx-auto mb-4">
                    <i class="fas fa-heartbeat text-4xl text-emerald-600"></i>
                </div>
                <h1 class="text-3xl font-black text-white mb-1">MAKOMBE</h1>
                <p class="text-emerald-100 text-sm">Portal do Paciente</p>
                <p class="text-emerald-200 text-xs italic mt-1">"Aqui você tem saúde"</p>
            </div>

            <!-- Form -->
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Bem-vindo! 👋</h2>

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('patient.login.post') }}" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-emerald-600 mr-2"></i>Email ou Telemóvel
                        </label>
                        <input type="text" name="identifier" value="{{ old('identifier') }}" required autofocus
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="paciente@email.com ou 841234567">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock text-emerald-600 mr-2"></i>Senha
                        </label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="••••••••">
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-emerald-600 rounded">
                            <span class="text-gray-600">Lembrar-me</span>
                        </label>
                    </div>

                    <button type="submit" 
                            class="w-full py-3 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold rounded-xl shadow-lg transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>ENTRAR
                    </button>
                </form>

                <div class="mt-6 space-y-3 text-center">
                    <a href="{{ route('patient.register') }}" class="block w-full py-3 px-4 border-2 border-emerald-600 text-emerald-600 hover:bg-emerald-50 font-semibold rounded-xl transition">
                        <i class="fas fa-user-plus mr-2"></i>Criar Conta de Paciente
                    </a>
                    <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-user-shield mr-1"></i> Acesso Staff/Admin
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>