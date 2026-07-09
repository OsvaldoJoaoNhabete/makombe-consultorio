<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conta Criada • Makombe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-emerald-500 to-teal-600 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden">
        
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-8 text-white text-center">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-2xl">
                <i class="fas fa-check text-5xl text-green-600"></i>
            </div>
            <h1 class="text-3xl font-black mb-2">Conta Criada com Sucesso!</h1>
            <p class="text-green-100">Bem-vindo ao Portal do Paciente Makombe</p>
        </div>

        <div class="p-8">
            <div class="mb-6 p-4 bg-amber-50 border-2 border-amber-300 rounded-xl">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-amber-600 text-2xl"></i>
                    <div>
                        <h3 class="font-bold text-amber-900 mb-1">⚠️ GUARDE ESTAS CREDENCIAIS!</h3>
                        <p class="text-sm text-amber-800">
                            Estas são as suas credenciais de acesso. <strong>Guarde-as em local seguro</strong>.
                            A senha não será exibida novamente.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-6 bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-5">
                <h3 class="font-bold text-blue-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-id-card"></i> Suas Credenciais
                </h3>
                
                <div class="space-y-3">
                    <div class="bg-white rounded-lg p-3 border border-blue-200 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">NID</p>
                            <p class="text-lg font-mono font-bold text-gray-900">{{ $nid }}</p>
                        </div>
                        <button onclick="copyText('{{ $nid }}')" class="px-3 py-2 bg-blue-600 text-white text-xs rounded-lg">
                            <i class="fas fa-copy"></i> Copiar
                        </button>
                    </div>

                    <div class="bg-white rounded-lg p-3 border border-blue-200 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Email</p>
                            <p class="text-sm font-mono font-bold text-gray-900">{{ $email }}</p>
                        </div>
                        <button onclick="copyText('{{ $email }}')" class="px-3 py-2 bg-blue-600 text-white text-xs rounded-lg">
                            <i class="fas fa-copy"></i> Copiar
                        </button>
                    </div>

                    <div class="bg-white rounded-lg p-3 border border-blue-200 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Telefone</p>
                            <p class="text-sm font-mono font-bold text-gray-900">+258 {{ $phone }}</p>
                        </div>
                        <button onclick="copyText('+258{{ $phone }}')" class="px-3 py-2 bg-blue-600 text-white text-xs rounded-lg">
                            <i class="fas fa-copy"></i> Copiar
                        </button>
                    </div>
                </div>
            </div>

            <a href="{{ route('patient.dashboard') }}" 
               class="block w-full py-3 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-xl text-center shadow-lg">
                <i class="fas fa-arrow-right mr-2"></i>ACEDER AO MEU PORTAL
            </a>
        </div>
    </div>

    <script>
        function copyText(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Copiado: ' + text);
            });
        }
    </script>
</body>
</html>