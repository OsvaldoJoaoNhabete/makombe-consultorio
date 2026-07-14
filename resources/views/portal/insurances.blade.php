<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Seguradoras • Portal do Paciente - Makombe</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
    body { font-family: 'Lato', sans-serif; }
    
    .sidebar { 
        position: fixed; top: 0; left: 0; height: 100vh; width: 270px; 
        background: linear-gradient(180deg, #4c1d95 0%, #5b21b6 50%, #6d28d9 100%); 
        display: flex; flex-direction: column; z-index: 50; 
        transition: transform 0.3s ease;
    }
    
    .nav-item { 
        display: flex; align-items: center; padding: 0.85rem 1.5rem; 
        color: #ddd6fe; text-decoration: none; transition: all 0.2s ease; 
        font-size: 0.95rem; font-weight: 500; border-left: 4px solid transparent; 
    }
    .nav-item:hover { 
        background-color: rgba(255, 255, 255, 0.1); 
        color: #ffffff; 
        border-left-color: #a78bfa;
    }
    .nav-item.active { 
        background-color: rgba(255, 255, 255, 0.15); 
        color: #ffffff; 
        border-left-color: #ffffff;
        font-weight: 700;
    }
    .nav-item i { width: 24px; text-align: center; margin-right: 12px; font-size: 1.1rem; }
    
    .main-content { margin-left: 270px; min-height: 100vh; background-color: #f8fafc; }
    
    @media (max-width: 768px) { 
        .sidebar { transform: translateX(-100%); } 
        .sidebar.open { transform: translateX(0); } 
        .main-content { margin-left: 0; } 
    }
</style>
</head>
<body class="bg-gray-50">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

    <x-portal-sidebar />

    <div class="main-content">
        <header class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <button class="md:hidden text-gray-600" onclick="toggleSidebar()"><i class="fas fa-bars text-xl"></i></button>
                <h2 class="text-xl font-bold text-gray-800">Minhas Seguradoras</h2>
                <div></div>
            </div>
        </header>

        <main class="p-6">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">🛡️ Minhas Seguradoras</h1>
                <p class="text-gray-600">Gerencie suas apólices de seguro de saúde.</p>
            </div>

            @if($insurances->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($insurances as $insurance)
                        <div class="bg-white rounded-xl shadow-sm border overflow-hidden hover:shadow-lg transition">
                            <div class="p-6 border-b bg-gradient-to-br from-blue-50 to-indigo-50">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-white rounded-lg shadow flex items-center justify-center">
                                        <i class="fas fa-shield-alt text-2xl text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900">{{ $insurance->name }}</h3>
                                        @if($insurance->pivot->is_primary)
                                            <span class="inline-block mt-1 px-2 py-0.5 bg-blue-600 text-white text-xs font-semibold rounded-full">
                                                Principal
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 space-y-3">
                                @if($insurance->pivot->policy_number)
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold">Número da Apólice</p>
                                        <p class="text-sm font-mono font-bold text-gray-900">{{ $insurance->pivot->policy_number }}</p>
                                    </div>
                                @endif

                                @if($insurance->pivot->valid_from || $insurance->pivot->valid_until)
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold">Validade</p>
                                        <p class="text-sm text-gray-900">
                                            {{ $insurance->pivot->valid_from?->format('d/m/Y') ?? 'N/A' }} 
                                            até 
                                            {{ $insurance->pivot->valid_until?->format('d/m/Y') ?? 'N/A' }}
                                        </p>
                                    </div>
                                @endif

                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Status</p>
                                    <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full {{ $insurance->pivot->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $insurance->pivot->is_active ? '✅ Ativa' : '❌ Inativa' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
                    <i class="fas fa-shield-alt text-6xl text-gray-300 mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Nenhuma seguradora vinculada</h4>
                    <p class="text-gray-500 mb-4">
                        Para vincular uma seguradora, contacte a recepção do consultório.
                    </p>
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 max-w-md mx-auto">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Como funciona?</strong><br>
                            Ao fazer uma consulta, você pode selecionar uma seguradora para cobertura parcial ou total do valor.
                        </p>
                    </div>
                </div>
            @endif
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebar-overlay').classList.toggle('hidden');
        }
    </script>
</body>
</html>