<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Negado - Makombe Consultório Médico</title>
    
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

    <div class="text-center bg-white p-10 rounded-2xl shadow-2xl border border-gray-100 max-w-lg w-full">
        
        <!-- Ícone Animado -->
        <div class="w-28 h-28 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
            <i class="fas fa-lock text-5xl text-red-500"></i>
        </div>
        
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Acesso Negado</h1>
        <p class="text-lg text-gray-600 mb-2">Erro 403</p>
        
        <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-lg mb-8 text-left">
            <p class="text-sm text-amber-800 font-medium">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Ops! Você não possui as permissões (roles) necessárias para aceder a esta página.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="javascript:history.back()" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                <i class="fas fa-home"></i> Ir para o Dashboard
            </a>
        </div>
        
        <p class="mt-8 text-xs text-gray-400">
            Se acredita que isto é um erro, contacte o administrador do sistema.
        </p>
    </div>

</body>
</html>