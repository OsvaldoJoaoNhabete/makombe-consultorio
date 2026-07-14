<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidade • Makombe Consultório Médico</title>
    
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
<body class="gradient-bg min-h-screen">

    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('patient.register') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-heartbeat text-white"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-800">MAKOMBE</h1>
                    <p class="text-xs text-gray-500 italic">"Aqui você tem saúde"</p>
                </div>
            </a>
            <a href="{{ route('patient.register') }}" class="text-sm text-emerald-600 hover:text-emerald-800 font-semibold">
                <i class="fas fa-arrow-left mr-1"></i> Voltar ao Registo
            </a>
        </div>
    </div>

    <!-- Conteúdo -->
    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 p-8 text-white">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-shield-alt text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black">Política de Privacidade</h1>
                        <p class="text-emerald-100 text-sm mt-1">Proteção dos seus Dados Pessoais</p>
                        <p class="text-emerald-200 text-xs mt-1">Última atualização: {{ date('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Conteúdo -->
            <div class="p-6 lg:p-10 prose max-w-none">
                
                <div class="mb-8 p-4 bg-green-50 border border-green-200 rounded-xl">
                    <p class="text-sm text-green-800">
                        <strong>🔒 A SUA PRIVACIDADE É IMPORTANTE PARA NÓS.</strong> 
                        Esta política explica como recolhemos, usamos e protegemos as suas informações pessoais, em conformidade 
                        com a Lei n.º 19/2022 de Proteção de Dados Pessoais de Moçambique.
                    </p>
                </div>

                <h2>1. Dados que Recolhemos</h2>
                <p>Recolhemos os seguintes tipos de dados:</p>
                
                <h3>📋 Dados Pessoais:</h3>
                <ul>
                    <li>Nome completo</li>
                    <li>Número de Identificação (NID) - gerado automaticamente</li>
                    <li>Número do Bilhete de Identidade</li>
                    <li>Data de nascimento</li>
                    <li>Género</li>
                    <li>Número de telefone</li>
                    <li>Endereço de email</li>
                    <li>Morada</li>
                </ul>

                <h3>🏥 Dados de Saúde:</h3>
                <ul>
                    <li>Histórico médico e alergias</li>
                    <li>Diagnósticos e prescrições</li>
                    <li>Resultados de exames</li>
                    <li>Informações sobre consultas</li>
                    <li>Dados de seguradoras e apólices</li>
                </ul>

                <h3>💻 Dados Técnicos:</h3>
                <ul>
                    <li>Endereço IP</li>
                    <li>Tipo de dispositivo e navegador</li>
                    <li>Registo de atividades no Portal</li>
                </ul>

                <h2>2. Como Utilizamos os Seus Dados</h2>
                <p>Utilizamos os seus dados para:</p>
                <ul>
                    <li><strong>Prestar serviços médicos</strong> - agendamento e realização de consultas</li>
                    <li><strong>Comunicação</strong> - enviar lembretes, confirmações e notificações</li>
                    <li><strong>Faturação</strong> - processar pagamentos e emitir facturas</li>
                    <li><strong>Seguradoras</strong> - processar reembolsos e coberturas</li>
                    <li><strong>Melhoria do serviço</strong> - analisar e melhorar a qualidade dos cuidados</li>
                    <li><strong>Cumprimento legal</strong> - obedecer a obrigações legais e regulatórias</li>
                </ul>

                <h2>3. Base Legal para o Tratamento</h2>
                <p>Tratamos os seus dados com base em:</p>
                <ul>
                    <li><strong>Consentimento</strong> - quando nos autoriza explicitamente</li>
                    <li><strong>Execução de contrato</strong> - para prestar os serviços solicitados</li>
                    <li><strong>Obrigação legal</strong> - cumprimento de leis aplicáveis</li>
                    <li><strong>Interesse legítimo</strong> - melhoria dos nossos serviços</li>
                </ul>

                <h2>4. Partilha de Dados</h2>
                <p>
                    <strong>Nunca vendemos os seus dados.</strong> Podemos partilhar informações apenas com:
                </p>
                <ul>
                    <li><strong>Médicos e profissionais de saúde</strong> envolvidos no seu tratamento</li>
                    <li><strong>Seguradoras</strong> - para processamento de coberturas</li>
                    <li><strong>Laboratórios</strong> - quando são solicitados exames</li>
                    <li><strong>Autoridades de saúde</strong> - quando exigido por lei</li>
                    <li><strong>Fornecedores de tecnologia</strong> - que nos ajudam a operar o Portal (sob contrato de confidencialidade)</li>
                </ul>

                <h2>5. Segurança dos Dados</h2>
                <p>
                    Implementamos medidas de segurança robustas para proteger os seus dados:
                </p>
                <ul>
                    <li>🔐 <strong>Encriptação</strong> de dados sensíveis (SSL/TLS)</li>
                    <li>🔑 <strong>Senhas encriptadas</strong> com algoritmos seguros (bcrypt)</li>
                    <li>🛡️ <strong>Firewalls</strong> e proteção contra ataques</li>
                    <li>📝 <strong>Registo de atividades</strong> (audit logs)</li>
                    <li>👥 <strong>Acesso restrito</strong> apenas a profissionais autorizados</li>
                    <li>💾 <strong>Backups regulares</strong> com encriptação</li>
                </ul>

                <h2>6. Período de Conservação</h2>
                <p>Conservamos os seus dados pelo tempo necessário para:</p>
                <ul>
                    <li><strong>Dados clínicos:</strong> mínimo de 20 anos (conforme legislação de saúde)</li>
                    <li><strong>Dados de faturação:</strong> 10 anos (obrigação fiscal)</li>
                    <li><strong>Dados de conta:</strong> enquanto a conta estiver ativa</li>
                    <li><strong>Logs de atividade:</strong> 2 anos</li>
                </ul>

                <h2>7. Os Seus Direitos (Lei 19/2022)</h2>
                <p>De acordo com a Lei de Proteção de Dados de Moçambique, você tem direito a:</p>
                <ul>
                    <li>✅ <strong>Acesso</strong> - saber que dados temos sobre si</li>
                    <li>✅ <strong>Retificação</strong> - corrigir dados incorretos</li>
                    <li>✅ <strong>Apagar</strong> - solicitar a eliminação dos dados (com exceções legais)</li>
                    <li>✅ <strong>Limitar</strong> - restringir o tratamento dos dados</li>
                    <li>✅ <strong>Portabilidade</strong> - receber os seus dados em formato estruturado</li>
                    <li>✅ <strong>Opor-se</strong> - opor-se ao tratamento em certas circunstâncias</li>
                    <li>✅ <strong>Retirar consentimento</strong> - a qualquer momento</li>
                </ul>

                <h2>8. Cookies e Tecnologias Semelhantes</h2>
                <p>Utilizamos cookies essenciais para o funcionamento do Portal, incluindo:</p>
                <ul>
                    <li>Cookies de sessão (para manter o login)</li>
                    <li>Cookies de segurança (proteção CSRF)</li>
                    <li>Cookies de preferência (idioma, tema)</li>
                </ul>

                <h2>9. Transferências Internacionais</h2>
                <p>
                    Os seus dados são armazenados principalmente em Moçambique. Em casos excecionais, podem ser transferidos 
                    para países com nível adequado de proteção, sempre com as garantias legais apropriadas.
                </p>

                <h2>10. Menores de Idade</h2>
                <p>
                    O Portal é destinado a maiores de 18 anos. Para menores, o registo deve ser feito por pais ou tutores 
                    legais, que são responsáveis pelas informações fornecidas.
                </p>

                <h2>11. Alterações a Esta Política</h2>
                <p>
                    Podemos atualizar esta Política periodicamente. Alterações significativas serão comunicadas por email 
                    ou através do Portal. A data da última atualização é indicada no topo deste documento.
                </p>

                <h2>12. Encarregado de Proteção de Dados</h2>
                <p>
                    Para exercer os seus direitos ou esclarecer dúvidas sobre a proteção dos seus dados, contacte:
                </p>
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-4">
                    <p class="text-gray-700"><strong>Encarregado de Proteção de Dados</strong></p>
                    <p class="text-gray-600 text-sm mt-2">
                        📧 dpo@makombe.co.mz<br>
                        📞 +258 84 123 4567<br>
                        📍 Makombe Consultório Médico, Maputo, Moçambique
                    </p>
                </div>

                <h2>13. Contacto</h2>
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-4">
                    <p class="text-gray-700"><strong>Makombe Consultório Médico</strong></p>
                    <p class="text-gray-600 text-sm mt-2">
                        📍 Maputo, Moçambique<br>
                        📞 +258 84 123 4567<br>
                        📧 info@makombe.co.mz<br>
                        🌐 www.makombe.co.mz
                    </p>
                </div>

                <div class="mt-10 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
                    <p>© {{ date('Y') }} Makombe Consultório Médico. Todos os direitos reservados.</p>
                    <p class="mt-2">
                        <a href="{{ route('patient.terms') }}" class="text-emerald-600 hover:underline">Termos e Condições</a>
                        <span class="mx-2">•</span>
                        <a href="{{ route('patient.register') }}" class="text-emerald-600 hover:underline">Voltar ao Registo</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>