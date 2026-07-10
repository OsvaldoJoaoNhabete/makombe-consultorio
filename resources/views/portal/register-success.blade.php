<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conta Criada • Makombe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Lato', sans-serif; }
        
        /* CSS de Impressão - Apenas credenciais aparecem */
        @media print {
            @page {
                size: A5 portrait;
                margin: 8mm;
            }
            
            body * {
                visibility: hidden !important;
            }
            
            #printArea, #printArea * {
                visibility: visible !important;
            }
            
            #printArea {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-emerald-500 to-teal-600 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-2xl">
        
        {{-- ÁREA IMPRESSA (invisível na tela, visível ao imprimir) --}}
        <div id="printArea" class="hidden">
            <div style="font-family: 'Lato', Arial, sans-serif; color: #1f2937; max-width: 140mm; margin: 0 auto; padding: 5mm;">
                
                <!-- Cabeçalho Compacto -->
                <div style="border-bottom: 3px solid #10b981; padding-bottom: 8px; margin-bottom: 10px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 40px; vertical-align: middle;">
                                <div style="width: 35px; height: 35px; background: #10b981; border-radius: 6px; color: white; text-align: center; line-height: 35px; font-size: 18px; font-weight: bold;">M</div>
                            </td>
                            <td style="vertical-align: middle; padding-left: 8px;">
                                <h1 style="margin: 0; font-size: 16px; color: #10b981; font-weight: 900;">MAKOMBE</h1>
                                <p style="margin: 0; font-size: 8px; color: #6b7280; font-style: italic;">Consultório Médico</p>
                            </td>
                            <td style="text-align: right; vertical-align: middle; font-size: 8px; color: #9ca3af;">
                                {{ now()->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Título -->
                <div style="text-align: center; margin-bottom: 10px;">
                    <h2 style="margin: 0; font-size: 13px; color: #1f2937; font-weight: bold;">CREDENCIAIS DE ACESSO</h2>
                    <p style="margin: 2px 0 0 0; font-size: 8px; color: #6b7280;">⚠️ GUARDE ESTE DOCUMENTO</p>
                </div>

                <!-- Credenciais -->
                <div style="border: 2px solid #10b981; border-radius: 4px; padding: 8px; margin-bottom: 8px;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 4px 6px; color: #6b7280; font-weight: 600; width: 30%;">Paciente:</td>
                            <td style="padding: 4px 6px; color: #1f2937; font-weight: bold;">{{ \App\Models\Patient::where('nid', $nid)->first()?->full_name ?? 'N/A' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 4px 6px; color: #6b7280; font-weight: 600;">NID:</td>
                            <td style="padding: 4px 6px; color: #1f2937; font-family: monospace; font-weight: bold;">{{ $nid }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 4px 6px; color: #6b7280; font-weight: 600;">Email:</td>
                            <td style="padding: 4px 6px; color: #1f2937; font-family: monospace;">{{ $email }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 4px 6px; color: #6b7280; font-weight: 600;">Telefone:</td>
                            <td style="padding: 4px 6px; color: #1f2937; font-family: monospace;">+258 {{ $phone }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 6px; color: #dc2626; font-weight: 700;">SENHA:</td>
                            <td style="padding: 4px 6px; color: #dc2626; font-family: monospace; font-weight: 900; font-size: 13px; letter-spacing: 2px;">{{ $password }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Link -->
                <div style="background: #eff6ff; padding: 5px 8px; border-radius: 3px; margin-bottom: 8px; text-align: center;">
                    <p style="margin: 0; font-size: 8px; color: #1e40af; font-weight: 600;">ACESSE:</p>
                    <p style="margin: 2px 0 0 0; font-size: 10px; color: #1e40af; font-family: monospace; font-weight: bold;">
                        {{ route('patient.login') }}
                    </p>
                </div>

                <!-- Aviso -->
                <div style="background: #fef3c7; border-left: 3px solid #f59e0b; padding: 6px 8px; border-radius: 3px; margin-bottom: 6px;">
                    <p style="margin: 0 0 3px 0; font-size: 8px; color: #92400e; font-weight: 700;">⚠️ IMPORTANTE:</p>
                    <ul style="margin: 0; padding-left: 12px; font-size: 7px; color: #78350f; line-height: 1.3;">
                        <li>Após o 1º login, crie uma senha personalizada</li>
                        <li>Não partilhe estas credenciais</li>
                        <li>Em caso de perda: "Esqueci minha senha"</li>
                    </ul>
                </div>

                <!-- Rodapé -->
                <div style="border-top: 1px solid #e5e7eb; padding-top: 5px; text-align: center; font-size: 7px; color: #9ca3af;">
                    Makombe Consultório Médico • +258 84 123 4567 • info@makombe.co.mz
                </div>
            </div>
        </div>

        {{-- ÁREA VISÍVEL NA TELA --}}
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden no-print">
            
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-8 text-white text-center">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-2xl">
                    <i class="fas fa-check text-5xl text-green-600"></i>
                </div>
                <h1 class="text-3xl font-black mb-2">Conta Criada com Sucesso!</h1>
                <p class="text-green-100">Bem-vindo(a) ao Portal do Paciente Makombe</p>
            </div>

            <div class="p-8">
                <!-- ALERTA -->
                <div class="mb-6 p-4 bg-red-50 border-2 border-red-400 rounded-xl">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                        <div>
                            <h3 class="font-bold text-red-900 mb-1">⚠️ MUITO IMPORTANTE!</h3>
                            <p class="text-sm text-red-800">
                                <strong>GUARDE ESTAS CREDENCIAIS!</strong><br>
                                Esta é a única vez que verá sua senha. Anote num papel ou use um gestor de senhas.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- CREDENCIAIS -->
                <div class="mb-6 bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-5">
                    <h3 class="font-bold text-blue-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-id-card"></i> Suas Credenciais de Acesso
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="bg-white rounded-lg p-3 border border-blue-200 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">NID</p>
                                <p class="text-lg font-mono font-bold text-gray-900">{{ $nid }}</p>
                            </div>
                            <button onclick="copyText('{{ $nid }}', this)" class="px-3 py-2 bg-blue-600 text-white text-xs rounded-lg">
                                <i class="fas fa-copy"></i> Copiar
                            </button>
                        </div>

                        <div class="bg-white rounded-lg p-3 border border-blue-200 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Email</p>
                                <p class="text-sm font-mono font-bold text-gray-900">{{ $email }}</p>
                            </div>
                            <button onclick="copyText('{{ $email }}', this)" class="px-3 py-2 bg-blue-600 text-white text-xs rounded-lg">
                                <i class="fas fa-copy"></i> Copiar
                            </button>
                        </div>

                        <div class="bg-white rounded-lg p-3 border border-blue-200 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Telefone</p>
                                <p class="text-sm font-mono font-bold text-gray-900">+258 {{ $phone }}</p>
                            </div>
                            <button onclick="copyText('+258{{ $phone }}', this)" class="px-3 py-2 bg-blue-600 text-white text-xs rounded-lg">
                                <i class="fas fa-copy"></i> Copiar
                            </button>
                        </div>

                        <div class="bg-white rounded-lg p-3 border-2 border-red-300 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-red-600 uppercase font-bold">🔐 Senha Temporária</p>
                                <p class="text-2xl font-mono font-black text-red-700 tracking-wider">{{ $password }}</p>
                            </div>
                            <button onclick="copyText('{{ $password }}', this)" class="px-3 py-2 bg-red-600 text-white text-xs rounded-lg">
                                <i class="fas fa-copy"></i> Copiar
                            </button>
                        </div>
                    </div>

                    <button onclick="copyAllCredentials()" 
                            class="mt-4 w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-clipboard"></i> COPIAR TODAS AS CREDENCIAIS
                    </button>
                </div>

                <!-- Recomendações -->
                <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-5">
                    <h3 class="font-bold text-emerald-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-shield-alt"></i> Recomendações de Segurança
                    </h3>
                    <ul class="space-y-2 text-sm text-emerald-800">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-emerald-600 mt-0.5"></i>
                            <span><strong>Anote suas credenciais</strong> num papel e guarde em local seguro</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-emerald-600 mt-0.5"></i>
                            <span><strong>Após o primeiro login</strong>, crie uma senha personalizada</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-emerald-600 mt-0.5"></i>
                            <span><strong>Nunca partilhe</strong> suas credenciais com terceiros</span>
                        </li>
                    </ul>
                </div>

                <!-- Botões -->
                <button onclick="window.print()" 
                        class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl text-center shadow-lg transition flex items-center justify-center gap-2">
                    <i class="fas fa-print"></i> IMPRIMIR CREDENCIAIS
                </button>

                <a href="{{ route('patient.dashboard') }}" 
                   class="mt-3 block w-full py-3 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-xl text-center shadow-lg">
                    <i class="fas fa-arrow-right mr-2"></i> ACEDER AO MEU PORTAL
                </a>
            </div>
        </div>
    </div>

    <script>
        function copyText(text, button) {
            navigator.clipboard.writeText(text).then(() => {
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i> Copiado!';
                button.classList.remove('bg-blue-600', 'bg-red-600');
                button.classList.add('bg-green-600');
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('bg-green-600');
                    if (text === '{{ $password }}') button.classList.add('bg-red-600');
                    else button.classList.add('bg-blue-600');
                }, 2000);
            });
        }

        function copyAllCredentials() {
            const all = `CREDENCIAIS MAKOMBE\n====================\nNID: {{ $nid }}\nEmail: {{ $email }}\nTelefone: +258 {{ $phone }}\nSenha: {{ $password }}\nAcesso: {{ route('patient.login') }}`;
            navigator.clipboard.writeText(all).then(() => alert('✅ Credenciais copiadas!'));
        }
    </script>
</body>
</html>