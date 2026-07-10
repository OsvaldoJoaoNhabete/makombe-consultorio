<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pagamentos • Portal do Paciente - Makombe</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Lato', sans-serif; }
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 260px; background: white; border-right: 1px solid #e5e7eb; display: flex; flex-direction: column; z-index: 50; }
        .nav-item { display: flex; align-items: center; padding: 0.75rem 1.5rem; color: #4b5563; text-decoration: none; transition: all 0.2s; font-size: 0.95rem; font-weight: 500; border-left: 4px solid transparent; }
        .nav-item:hover { background-color: #f9fafb; color: #10b981; }
        .nav-item.active { background-color: #ecfdf5; color: #10b981; border-left-color: #10b981; }
        .main-content { margin-left: 260px; min-height: 100vh; background-color: #f9fafb; }
        @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .sidebar.open { transform: translateX(0); } .main-content { margin-left: 0; } }
    </style>
</head>
<body class="bg-gray-50">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-heartbeat text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-800">MAKOMBE</h1>
                    <p class="text-xs text-gray-500 italic">Portal do Paciente</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 space-y-1">
            <a href="{{ route('patient.dashboard') }}" class="nav-item"><i class="fas fa-home w-6"></i> Dashboard</a>
            <a href="{{ route('patient.schedule') }}" class="nav-item"><i class="fas fa-calendar-plus w-6"></i> Agendar Consulta</a>
            <a href="{{ route('patient.consultations') }}" class="nav-item"><i class="fas fa-calendar-check w-6"></i> Minhas Consultas</a>
            <a href="{{ route('patient.quotes') }}" class="nav-item"><i class="fas fa-file-invoice-dollar w-6"></i> Cotações</a>
            <a href="{{ route('patient.payments') }}" class="nav-item active"><i class="fas fa-credit-card w-6"></i> Pagamentos</a>
            <a href="{{ route('patient.insurances') }}" class="nav-item"><i class="fas fa-shield-alt w-6"></i> Seguradoras</a>
            <a href="{{ route('patient.profile') }}" class="nav-item"><i class="fas fa-user w-6"></i> Meu Perfil</a>
        </nav>

        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr($patient->full_name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $patient->full_name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $patient->email }}</p>
                </div>
                <form method="POST" action="{{ route('patient.logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-600 transition"><i class="fas fa-sign-out-alt"></i></button>
                </form>
            </div>
        </div>
    </aside>

    <div class="main-content">
        <header class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <button class="md:hidden text-gray-600" onclick="toggleSidebar()"><i class="fas fa-bars text-xl"></i></button>
                <h2 class="text-xl font-bold text-gray-800">Meus Pagamentos</h2>
                <div></div>
            </div>
        </header>

        <main class="p-6">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">💳 Meus Pagamentos</h1>
                <p class="text-gray-600">Histórico de pagamentos e faturas.</p>
            </div>

            <!-- Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Pago</p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_pago'], 2, ',', '.') }}</p>
                            <p class="text-xs opacity-75 mt-1">MT</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-3xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Pendente</p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($stats['pendente'], 2, ',', '.') }}</p>
                            <p class="text-xs opacity-75 mt-1">MT</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Pagamentos -->
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                @if($payments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Valor</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Método</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Referência</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($payments as $payment)
                                    @php
                                        $statusClass = match($payment->status) {
                                            'confirmado' => 'bg-green-100 text-green-800',
                                            'pendente' => 'bg-amber-100 text-amber-800',
                                            'cancelado' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                        $methodIcons = [
                                            'mpesa' => '📱 M-Pesa',
                                            'emola' => '📱 e-Mola',
                                            'transferencia' => '🏦 Transferência',
                                            'numerario' => '💵 Numerário',
                                            'cheque' => '📄 Cheque',
                                            'cartao' => '💳 Cartão',
                                            'seguradora' => '🛡️ Seguradora',
                                        ];
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm">
                                            <p class="font-medium text-gray-900">{{ $payment->created_at->format('d/m/Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ $payment->created_at->format('H:i') }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-green-600">{{ number_format($payment->amount, 2, ',', '.') }} MT</p>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            {{ $methodIcons[$payment->method] ?? ucfirst($payment->method) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-mono text-gray-600">
                                            {{ $payment->reference ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($payments->hasPages())
                        <div class="px-6 py-4 border-t bg-gray-50">
                            {{ $payments->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <i class="fas fa-credit-card text-6xl text-gray-300 mb-4"></i>
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">Nenhum pagamento encontrado</h4>
                        <p class="text-gray-500 mb-4">
                            Os pagamentos aparecem aqui após serem processados pelo consultório.
                        </p>
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 max-w-md mx-auto">
                            <p class="text-sm text-blue-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Métodos de pagamento aceites:</strong><br>
                                • M-Pesa e e-Mola<br>
                                • Transferência bancária<br>
                                • Numerário no consultório<br>
                                • Cartão de débito/crédito<br>
                                • Cobertura por seguradora
                            </p>
                        </div>
                    </div>
                @endif
            </div>
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