<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidade - Makombe Consultório Médico</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f9fafb; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Cabeçalho -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('welcome') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo-mcm.png') }}" alt="Makombe" class="w-10 h-10 object-contain">
                <span class="text-xl font-bold text-gray-800">Makombe</span>
            </a>
            <a href="{{ route('welcome') }}" class="text-purple-600 hover:text-purple-800 font-medium text-sm flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Voltar à página inicial
            </a>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="flex-grow container mx-auto px-4 py-12 max-w-4xl">
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-4">Política de Privacidade</h1>
            
            <div class="prose max-w-none text-gray-600 space-y-4">
                <p>A sua privacidade é importante para nós. Esta Política de Privacidade descreve como o Makombe Consultório Médico recolhe, utiliza e protege as suas informações pessoais.</p>
                
                <h2 class="text-xl font-semibold text-gray-800 mt-6">1. Informações que Recolhemos</h2>
                <p>Podemos recolher os seguintes tipos de informações:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong>Dados de Identificação:</strong> Nome completo, número de BI/NIF, data de nascimento e género.</li>
                    <li><strong>Dados de Contacto:</strong> Número de telemóvel, endereço de e-mail e morada.</li>
                    <li><strong>Dados Clínicos:</strong> Histórico médico, alergias, medicamentos em uso e resultados de exames.</li>
                    <li><strong>Dados de Seguro:</strong> Nome da seguradora e número de apólice (se aplicável).</li>
                </ul>

                <h2 class="text-xl font-semibold text-gray-800 mt-6">2. Como Utilizamos as Informações</h2>
                <p>As suas informações são utilizadas exclusivamente para:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Prestar cuidados de saúde adequados e personalizados.</li>
                    <li>Gerir agendamentos, consultas e faturação.</li>
                    <li>Comunicar resultados de exames ou lembretes de consultas (via SMS ou WhatsApp).</li>
                    <li>Cumprir obrigações legais e regulatórias do setor da saúde em Moçambique.</li>
                </ul>

                <h2 class="text-xl font-semibold text-gray-800 mt-6">3. Proteção e Segurança dos Dados</h2>
                <p>Implementamos medidas de segurança técnicas e organizacionais robustas para proteger os seus dados contra acesso não autorizado, alteração, divulgação ou destruição. O acesso aos dados clínicos é estritamente controlado e limitado aos profissionais de saúde diretamente envolvidos no seu atendimento.</p>

                <h2 class="text-xl font-semibold text-gray-800 mt-6">4. Partilha de Informações</h2>
                <p>Não vendemos nem partilhamos as suas informações pessoais com terceiros, exceto quando necessário para:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Processar reclamações junto da sua seguradora de saúde.</li>
                    <li>Cumprir ordens judiciais ou requisitos legais das autoridades de saúde.</li>
                    <li>Encaminhamento para outros especialistas, mediante o seu consentimento prévio.</li>
                </ul>

                <h2 class="text-xl font-semibold text-gray-800 mt-6">5. Os Seus Direitos</h2>
                <p>Você tem o direito de aceder, corrigir ou solicitar a eliminação dos seus dados pessoais, bem como retirar o consentimento para o seu tratamento a qualquer momento, contactando a administração do consultório.</p>

                <h2 class="text-xl font-semibold text-gray-800 mt-6">6. Contacto</h2>
                <p>Se tiver dúvidas sobre esta Política de Privacidade, entre em contacto connosco através do e-mail <strong>info@makombe.co.mz</strong> ou pelo telefone <strong>+258 84 117 88 57</strong>.</p>
            </div>

            <div class="mt-8 pt-6 border-t">
                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl transition">
                    <i class="fas fa-home"></i> Voltar ao Início
                </a>
            </div>
        </div>
    </main>

    <!-- Rodapé -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="text-sm text-gray-400">&copy; {{ date('Y') }} <span class="text-purple-400 font-semibold">Makombe Consultório Médico</span>. Todos os direitos reservados.</p>
            <p class="text-xs text-gray-500 mt-2">"Aqui você tem saúde"</p>
        </div>
    </footer>

</body>
</html>