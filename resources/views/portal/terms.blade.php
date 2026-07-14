<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos e Condições • Makombe Consultório Médico</title>
    
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
                        <i class="fas fa-file-contract text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black">Termos e Condições</h1>
                        <p class="text-emerald-100 text-sm mt-1">Portal do Paciente Makombe</p>
                        <p class="text-emerald-200 text-xs mt-1">Última atualização: {{ date('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Conteúdo dos Termos -->
            <div class="p-6 lg:p-10 prose max-w-none">
                
                <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <p class="text-sm text-blue-800">
                        <strong>LEIA ATENTAMENTE</strong> estes Termos e Condições antes de utilizar o Portal do Paciente Makombe. 
                        Ao criar uma conta e utilizar os nossos serviços, você concorda com todos os termos aqui descritos.
                    </p>
                </div>

                <h2>1. Aceitação dos Termos</h2>
                <p>
                    Ao registar-se e utilizar o Portal do Paciente do Makombe Consultório Médico ("Portal"), você concorda em cumprir 
                    e ficar vinculado a estes Termos e Condições. Se não concordar com qualquer parte destes termos, não deve utilizar 
                    os nossos serviços.
                </p>

                <h2>2. Descrição do Serviço</h2>
                <p>O Portal do Paciente Makombe é uma plataforma digital que permite aos pacientes:</p>
                <ul>
                    <li>Agendar consultas presenciais, teleconsultas e visitas domiciliárias</li>
                    <li>Aceder ao histórico de consultas e diagnósticos</li>
                    <li>Visualizar prescrições médicas e receitas</li>
                    <li>Consultar cotações e facturas</li>
                    <li>Gerir informações pessoais e de seguradoras</li>
                    <li>Receber notificações sobre consultas e pagamentos</li>
                </ul>

                <h2>3. Registo e Conta do Paciente</h2>
                <p>
                    Para utilizar o Portal, você deve criar uma conta fornecendo informações verdadeiras, completas e atualizadas. 
                    Você é responsável por:
                </p>
                <ul>
                    <li>Manter a confidencialidade das suas credenciais de acesso</li>
                    <li>Todas as atividades que ocorram na sua conta</li>
                    <li>Notificar-nos imediatamente sobre qualquer uso não autorizado</li>
                    <li>Fornecer informações médicas verdadeiras e completas</li>
                </ul>

                <h2>4. Teleconsultas</h2>
                <p>As teleconsultas são realizadas através da plataforma Jitsi Meet. Ao utilizar este serviço, você concorda que:</p>
                <ul>
                    <li>É responsável por garantir uma conexão de internet adequada</li>
                    <li>Deve estar em local privado e adequado para a consulta</li>
                    <li>O consultório não se responsabiliza por problemas técnicos do seu dispositivo</li>
                    <li>Em casos de emergência, deve procurar atendimento presencial imediato</li>
                    <li>A teleconsulta não substitui consultas presenciais quando necessário</li>
                </ul>

                <h2>5. Cancelamentos e Reagendamentos</h2>
                <p>
                    Consultas podem ser canceladas ou reagendadas até 24 horas antes do horário marcado, sem custos. 
                    Cancelamentos com menos de 24 horas de antecedência podem estar sujeitos a taxas.
                </p>

                <h2>6. Pagamentos</h2>
                <p>Os pagamentos podem ser efetuados através de:</p>
                <ul>
                    <li>M-Pesa ou e-Mola</li>
                    <li>Transferência bancária</li>
                    <li>Numerário (no consultório)</li>
                    <li>Cartão de débito/crédito</li>
                    <li>Cobertura por seguradora (quando aplicável)</li>
                </ul>

                <h2>7. Conduta do Utilizador</h2>
                <p>Você concorda em não utilizar o Portal para:</p>
                <ul>
                    <li>Qualquer finalidade ilegal ou não autorizada</li>
                    <li>Transmitir vírus ou código malicioso</li>
                    <li>Tentar aceder a contas de outros utilizadores</li>
                    <li>Fornecer informações médicas falsas</li>
                    <li>Partilhar credenciais com terceiros</li>
                </ul>

                <h2>8. Propriedade Intelectual</h2>
                <p>
                    Todo o conteúdo do Portal (textos, imagens, logótipos, software) é propriedade do Makombe Consultório Médico 
                    e está protegido pelas leis de direitos de autor de Moçambique.
                </p>

                <h2>9. Limitação de Responsabilidade</h2>
                <p>O Makombe Consultório Médico não se responsabiliza por:</p>
                <ul>
                    <li>Interrupções temporárias do serviço por manutenção</li>
                    <li>Problemas técnicos do dispositivo ou internet do paciente</li>
                    <li>Uso indevido das informações médicas por terceiros</li>
                    <li>Decisões tomadas pelo paciente com base em informações do Portal sem consulta médica</li>
                </ul>

                <h2>10. Modificações dos Termos</h2>
                <p>
                    Reservamo-nos o direito de modificar estes Termos a qualquer momento. Alterações significativas serão 
                    comunicadas por email ou através do Portal. O uso continuado do serviço após alterações constitui aceitação 
                    dos novos termos.
                </p>

                <h2>11. Lei Aplicável</h2>
                <p>
                    Estes Termos são regidos pelas leis da República de Moçambique, incluindo a Lei n.º 19/2022 sobre 
                    Proteção de Dados Pessoais. Qualquer litígio será resolvido nos tribunais competentes de Maputo.
                </p>

                <h2>12. Contacto</h2>
                <p>Para questões sobre estes Termos, contacte-nos:</p>
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-4">
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
                        <a href="{{ route('patient.privacy') }}" class="text-emerald-600 hover:underline">Política de Privacidade</a>
                        <span class="mx-2">•</span>
                        <a href="{{ route('patient.register') }}" class="text-emerald-600 hover:underline">Voltar ao Registo</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>