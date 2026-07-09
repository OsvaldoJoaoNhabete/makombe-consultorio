<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Staff • Makombe Consultório Médico</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Lato', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            
            <!-- Header -->
            <div class="bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-700 px-8 py-10 text-center">
                <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center shadow-xl mx-auto mb-4">
                    <i class="fas fa-heartbeat text-4xl text-blue-600"></i>
                </div>
                <h1 class="text-3xl font-black text-white mb-1">MAKOMBE</h1>
                <p class="text-blue-100 text-sm">Consultório Médico</p>
                <p class="text-blue-200 text-xs italic mt-1">"Aqui você tem saúde"</p>
            </div>

            <!-- Form -->
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Bem-vindo, Staff! 👋</h2>

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('staff.login') }}" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope text-blue-600 mr-2"></i>Email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="admin@makombe.co.mz">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock text-blue-600 mr-2"></i>Senha
                        </label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="••••••••">
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 rounded">
                            <span class="text-gray-600">Lembrar-me</span>
                        </label>
                    </div>

                    <button type="submit" 
                            class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>ENTRAR
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('patient.login') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        <i class="fas fa-user mr-1"></i> Portal do Paciente
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center mt-6 text-xs text-white/80">
            <p>© {{ date('Y') }} Makombe Consultório Médico</p>
        </div>
    </div>

</body>
</html>