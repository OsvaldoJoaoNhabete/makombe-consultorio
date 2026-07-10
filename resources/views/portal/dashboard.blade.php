<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard • Portal do Paciente - Makombe</title>
    
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

        @media print {
        /* Esconder tudo exceto o modal de credenciais */
        body * {
            visibility: hidden !important;
        }
        
        #credentialsPrintArea, #credentialsPrintArea * {
            visibility: visible !important;
        }
        
        #credentialsPrintArea {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            padding: 10mm !important;
            background: white !important;
            color: black !important;
        }
        
        /* Esconder botões e elementos interativos */
        .no-print, button, .btn, nav, header, aside, footer {
            display: none !important;
        }
        
        /* Remover margens da página */
        @page {
            size: A4;
            margin: 10mm;
        }
    }
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
            <a href="{{ route('patient.dashboard') }}" class="nav-item active">
                <i class="fas fa-home w-6"></i> Dashboard
            </a>
            <a href="{{ route('patient.schedule') }}" class="nav-item">
                <i class="fas fa-calendar-plus w-6"></i> Agendar Consulta
            </a>
            <a href="{{ route('patient.consultations') }}" class="nav-item">
                <i class="fas fa-calendar-check w-6"></i> Minhas Consultas
            </a>
            <a href="{{ route('patient.quotes') }}" class="nav-item">
                <i class="fas fa-file-invoice-dollar w-6"></i> Cotações
            </a>
            <a href="{{ route('patient.payments') }}" class="nav-item">
                <i class="fas fa-credit-card w-6"></i> Pagamentos
            </a>
            <a href="{{ route('patient.insurances') }}" class="nav-item">
                <i class="fas fa-shield-alt w-6"></i> Seguradoras
            </a>
            <a href="{{ route('patient.profile') }}" class="nav-item">
                <i class="fas fa-user w-6"></i> Meu Perfil
            </a>
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
                    <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="Sair">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="main-content">
        <header class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <button class="md:hidden text-gray-600" onclick="toggleSidebar()">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="text-xl font-bold text-gray-800">Dashboard</h2>
                <div></div>
            </div>
        </header>

        <main class="p-6">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 flex items-center gap-3">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Olá, {{ explode(' ', $patient->full_name)[0] }}! 👋</h1>
                <p class="text-gray-600">Bem-vindo ao seu portal de saúde.</p>
            </div>

            {{-- Modal de Primeiro Acesso - Alterar Senha --}}
@if($patient->needsPasswordChange())
<div id="firstAccessModal" class="fixed inset-0 bg-black bg-opacity-60 z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-6 py-5 text-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-key text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold">Bem-vindo(a), {{ explode(' ', $patient->full_name)[0] }}!</h2>
                    <p class="text-amber-100 text-sm">Primeiro acesso detectado</p>
                </div>
            </div>
        </div>

        <!-- Conteúdo -->
        <div class="p-6">
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-amber-900">
                    <i class="fas fa-shield-alt text-amber-600 mr-1"></i>
                    <strong>Por sua segurança</strong>, pedimos que crie uma senha personalizada. 
                    A senha temporária que recebeu não é segura para uso prolongado.
                </p>
            </div>

            <div class="space-y-3">
                <div class="flex items-start gap-2 text-sm text-gray-700">
                    <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                    <span>Crie uma senha com <strong>mínimo 6 caracteres</strong></span>
                </div>
                <div class="flex items-start gap-2 text-sm text-gray-700">
                    <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                    <span>Use letras, números ou símbolos</span>
                </div>
                <div class="flex items-start gap-2 text-sm text-gray-700">
                    <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                    <span>Guarde a nova senha em local seguro</span>
                </div>
            </div>
        </div>

        <!-- Botões -->
        <div class="px-6 py-4 bg-gray-50 flex flex-col gap-2">
            <a href="{{ route('patient.password.change') }}" 
               class="w-full py-3 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold rounded-xl text-center transition">
                <i class="fas fa-lock mr-2"></i> CRIAR MINHA SENHA AGORA
            </a>
            <form method="POST" action="{{ route('patient.password.postpone') }}">
                @csrf
                <button type="submit" 
                        class="w-full py-2 px-4 text-gray-600 hover:text-gray-800 text-sm font-medium transition">
                    <i class="fas fa-clock mr-1"></i> Fazer depois (não recomendado)
                </button>
            </form>
        </div>
    </div>
</div>
@endif

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white p-5 rounded-xl shadow-sm border">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Total Consultas</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_consultas'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-amber-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-amber-600">Agendadas</p>
                            <p class="text-2xl font-bold text-amber-700">{{ $stats['consultas_agendadas'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-green-600">Concluídas</p>
                            <p class="text-2xl font-bold text-green-700">{{ $stats['consultas_concluidas'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-invoice text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-purple-600">Cotações</p>
                            <p class="text-2xl font-bold text-purple-700">{{ $stats['total_cotacoes'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-coins text-emerald-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-emerald-600">Total Pago</p>
                            <p class="text-xl font-bold text-emerald-700">{{ number_format($stats['total_pago'], 0, ',', '.') }} MT</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="px-6 py-4 border-b flex items-center justify-between">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-calendar-check text-blue-600"></i> Próximas Consultas
                        </h3>
                        <a href="{{ route('patient.consultations') }}" class="text-sm text-blue-600 hover:underline">Ver todas</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($upcomingConsultations as $consultation)
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-stethoscope text-blue-600"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900">
                                            {{ $consultation->scheduled_at->format('d/m/Y \à\s H:i') }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-user-md mr-1"></i> {{ $consultation->doctor->name ?? 'Médico' }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $consultation->type === 'presencial' ? '🏥 Presencial' : ($consultation->type === 'teleconsulta' ? '💻 Teleconsulta' : '🏠 Domicílio') }}
                                        </p>

                                        @if($consultation->type === 'teleconsulta')
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                @if($consultation->isVideoCallActive())
                                                    <a href="{{ $consultation->location }}" target="_blank"
                                                       class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg">
                                                        <i class="fas fa-video"></i> Entrar na Videochamada
                                                    </a>
                                                @else
                                                    <a href="{{ route('patient.consultations.show', $consultation->id) }}"
                                                       class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg">
                                                        <i class="fas fa-hourglass-half"></i> Ver Credenciais
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                        {{ ucfirst($consultation->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">
                                <i class="fas fa-calendar text-4xl text-gray-300 mb-3"></i>
                                <p>Nenhuma consulta agendada</p>
                                <a href="{{ route('patient.schedule') }}" class="inline-block mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                    <i class="fas fa-plus mr-1"></i> Agendar Consulta
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="px-6 py-4 border-b">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-history text-gray-600"></i> Histórico de Consultas
                        </h3>
                    </div>
                    <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                        @forelse($pastConsultations as $consultation)
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm">
                                            {{ $consultation->scheduled_at->format('d/m/Y') }}
                                        </p>
                                        <p class="text-xs text-gray-600">
                                            Dr(a). {{ $consultation->doctor->name ?? '-' }}
                                        </p>
                                        @if($consultation->diagnosis)
                                            <p class="text-xs text-gray-500 mt-1">
                                                <strong>DX:</strong> {{ Str::limit($consultation->diagnosis, 80) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">
                                <i class="fas fa-history text-4xl text-gray-300 mb-3"></i>
                                <p>Nenhuma consulta concluída</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="px-6 py-4 border-b flex items-center justify-between">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-file-invoice-dollar text-purple-600"></i> Cotações Recentes
                        </h3>
                        <a href="{{ route('patient.quotes') }}" class="text-sm text-purple-600 hover:underline">Ver todas</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($recentQuotes as $quote)
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">Cotação #{{ str_pad($quote->id, 5, '0', STR_PAD_LEFT) }}</p>
                                        <p class="text-xs text-gray-600">{{ $quote->created_at->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900">{{ $quote->getFormattedTotal() }}</p>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $quote->getStatusBadgeClass() }}">
                                            {{ $quote->getStatusLabel() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">
                                <i class="fas fa-file-invoice text-4xl text-gray-300 mb-3"></i>
                                <p>Nenhuma cotação</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="px-6 py-4 border-b">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-credit-card text-emerald-600"></i> Pagamentos Recentes
                        </h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($recentPayments as $payment)
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ number_format($payment->amount, 2, ',', '.') }} MT</p>
                                        <p class="text-xs text-gray-600">{{ $payment->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    @php
                                        $statusClass = match($payment->status) {
                                            'confirmado' => 'bg-green-100 text-green-800',
                                            'pendente' => 'bg-amber-100 text-amber-800',
                                            'cancelado' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">
                                <i class="fas fa-credit-card text-4xl text-gray-300 mb-3"></i>
                                <p>Nenhum pagamento</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebar-overlay').classList.toggle('hidden');
        }
    </script>

{{-- Área de Impressão (invisível na tela, visível na impressão) --}}
<div id="credentialsPrintArea" class="hidden">
    <div style="font-family: 'Lato', Arial, sans-serif; color: #1f2937; max-width: 180mm; margin: 0 auto;">
        
        <!-- Cabeçalho -->
        <div style="border-bottom: 3px solid #10b981; padding-bottom: 10px; margin-bottom: 15px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 60px; vertical-align: middle;">
                        @if(file_exists(public_path('images/logo_makombe.jpg')))
                            <img src="{{ public_path('images/logo_makombe.jpg') }}" style="width: 50px; height: 50px; object-fit: contain;">
                        @else
                            <div style="width: 50px; height: 50px; background: #10b981; border-radius: 8px; color: white; text-align: center; line-height: 50px; font-size: 24px; font-weight: bold;">M</div>
                        @endif
                    </td>
                    <td style="vertical-align: middle;">
                        <h1 style="margin: 0; font-size: 20px; color: #10b981; font-weight: 900;">MAKOMBE</h1>
                        <p style="margin: 0; font-size: 10px; color: #6b7280; font-style: italic;">Consultório Médico • "Aqui você tem saúde"</p>
                        <p style="margin: 0; font-size: 9px; color: #9ca3af;">Maputo, Moçambique • +258 84 123 4567</p>
                    </td>
                    <td style="text-align: right; vertical-align: middle;">
                        <p style="margin: 0; font-size: 9px; color: #6b7280;">Emitido em:</p>
                        <p style="margin: 0; font-size: 10px; font-weight: bold; color: #1f2937;">{{ now()->format('d/m/Y H:i') }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Título -->
        <div style="text-align: center; margin-bottom: 15px;">
            <h2 style="margin: 0; font-size: 16px; color: #1f2937; font-weight: bold;">CREDENCIAIS DE ACESSO AO PORTAL</h2>
            <p style="margin: 3px 0 0 0; font-size: 10px; color: #6b7280;">Guarde este documento em local seguro</p>
        </div>

        <!-- Dados do Paciente -->
        <div style="background: #f3f4f6; border-left: 4px solid #10b981; padding: 8px 12px; margin-bottom: 12px; border-radius: 4px;">
            <table style="width: 100%; font-size: 10px;">
                <tr>
                    <td style="width: 30%; color: #6b7280; font-weight: 600;">Paciente:</td>
                    <td style="color: #1f2937; font-weight: bold;">{{ $patient->full_name }}</td>
                </tr>
                <tr>
                    <td style="color: #6b7280; font-weight: 600;">NID:</td>
                    <td style="color: #1f2937; font-family: monospace; font-weight: bold;">{{ $patient->nid }}</td>
                </tr>
            </table>
        </div>

        <!-- Credenciais -->
        <div style="border: 2px solid #10b981; border-radius: 6px; padding: 10px; margin-bottom: 12px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 6px 8px; color: #6b7280; font-weight: 600; width: 35%;">Email de Login:</td>
                    <td style="padding: 6px 8px; color: #1f2937; font-family: monospace; font-weight: bold;">{{ $patient->email }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 6px 8px; color: #6b7280; font-weight: 600;">Telefone:</td>
                    <td style="padding: 6px 8px; color: #1f2937; font-family: monospace; font-weight: bold;">+258 {{ $patient->phone }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 8px; color: #dc2626; font-weight: 700;">Senha:</td>
                    <td style="padding: 6px 8px; color: #dc2626; font-family: monospace; font-weight: 900; font-size: 14px;">{{ $temporaryPassword ?? '********' }}</td>
                </tr>
            </table>
        </div>

        <!-- Link de Acesso -->
        <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 8px 12px; border-radius: 4px; margin-bottom: 12px; text-align: center;">
            <p style="margin: 0; font-size: 9px; color: #1e40af; font-weight: 600;">ENDEREÇO DE ACESSO:</p>
            <p style="margin: 3px 0 0 0; font-size: 11px; color: #1e40af; font-family: monospace; font-weight: bold;">
                {{ route('patient.login') }}
            </p>
        </div>

        <!-- Aviso de Segurança -->
        <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 8px 12px; border-radius: 4px; margin-bottom: 10px;">
            <p style="margin: 0 0 4px 0; font-size: 10px; color: #92400e; font-weight: 700;">
                ⚠️ INFORMAÇÕES IMPORTANTES:
            </p>
            <ul style="margin: 0; padding-left: 15px; font-size: 9px; color: #78350f; line-height: 1.4;">
                <li>Guarde este documento em local seguro e confidencial.</li>
                <li>Não partilhe suas credenciais com terceiros.</li>
                <li>Após o primeiro login, crie uma senha personalizada.</li>
                <li>Em caso de perda, use "Esqueci minha senha" no portal.</li>
            </ul>
        </div>

        <!-- Rodapé -->
        <div style="border-top: 2px solid #e5e7eb; padding-top: 8px; text-align: center; font-size: 8px; color: #9ca3af;">
            <p style="margin: 0;">Makombe Consultório Médico • Lei 19/2022 - Proteção de Dados Pessoais</p>
            <p style="margin: 2px 0 0 0;">info@makombe.co.mz • www.makombe.co.mz</p>
        </div>
    </div>
</div>

</body>
</html>