<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recuperar Senha - Makombe Consultório Médico</title>
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
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-800 font-medium text-sm">
                <i class="fas fa-arrow-left"></i> Voltar ao Login
            </a>
        </div>

        <div class="text-center mb-6">
            <img src="{{ asset('images/logo-mcm.png') }}" alt="Makombe" class="w-20 h-20 mx-auto mb-3 object-contain">
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Recuperar Acesso</h1>
            <p class="text-gray-600 text-sm">Insira o seu email ou telemóvel para receber as instruções.</p>
        </div>

        @if (session('status'))
            <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
                <p class="text-sm text-green-700 font-medium"><i class="fas fa-check-circle mr-2"></i>{{ session('status') }}</p>
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

        <form method="POST" action="{{ route('patient.password.recover') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email ou Telemóvel <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fas fa-user text-gray-400"></i></div>
                    <input type="text" name="identifier" required
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition"
                           placeholder="ex: email@makombe.com ou 841234567">
                </div>
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg transition transform hover:scale-[1.02] flex items-center justify-center gap-2">
                <i class="fas fa-paper-plane"></i> Enviar Instruções
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} <span class="text-purple-600 font-semibold">Makombe Consultório Médico</span>
        </div>
    </div>
</body>
</html>