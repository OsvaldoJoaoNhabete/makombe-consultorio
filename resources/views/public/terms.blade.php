<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos e Condições - Makombe Consultório Médico</title>
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
            <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-4">Termos e Condições</h1>
            
            <div class="prose max-w-none text-gray-600 space-y-4">
                <p>Bem-vindo ao Makombe Consultório Médico. Ao aceder e utilizar os nossos serviços, você concorda com os seguintes termos e condições.</p>
                
                <h2 class="text-xl font-semibold text-gray-800 mt-6">1. Aceitação dos Termos</h2>
                <p>Ao utilizar o nosso portal de pacientes ou serviços clínicos, você concorda em cumprir e estar vinculado a estes Termos e Condições. Se não concordar com qualquer parte destes termos, por favor, não utilize os nossos serviços.</p>

                <h2 class="text-xl font-semibold text-gray-800 mt-6">2. Serviços Prestados</h2>
                <p>O Makombe Consultório Médico oferece serviços de saúde, incluindo, mas não se limitando a:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong>Clínica Geral</strong> e <strong>Medicina Interna</strong></li>
                    <li><strong>Pediatria</strong>, <strong>Ginecologia e Obstetrícia</strong></li>
                    <li><strong>Dermatologia</strong>, <strong>Cardiologia</strong>, <strong>Urologia</strong></li>
                    <li><strong>Psicologia</strong>, <strong>Psiquiatria</strong>, <strong>Nutrição</strong></li>
                    <li><strong>Terapia da Fala</strong> e <strong>Terapia Ocupacional</strong></li>
                </ul>

                <h2 class="text-xl font-semibold text-gray-800 mt-6">3. Agendamento e Consultas</h2>
                <p>Os agendamentos de consultas estão sujeitos à disponibilidade dos profissionais. O paciente deve comparecer com pelo menos 15 minutos de antecedência. Em caso de impossibilidade, solicitamos o cancelamento com pelo menos 24 horas de antecedência.</p>

                <h2 class="text-xl font-semibold text-gray-800 mt-6">4. Confidencialidade e Dados Pessoais</h2>
                <p>Comprometemo-nos a proteger a sua privacidade. Todas as informações médicas e pessoais são tratadas com estrita confidencialidade, em conformidade com as leis de proteção de dados vigentes em Moçambique.</p>

                <h2 class="text-xl font-semibold text-gray-800 mt-6">5. Pagamentos e Seguros</h2>
                <p>Os valores das consultas e procedimentos serão informados previamente. Aceitamos pagamentos por dinheiro, transferência bancária e TPV. Para pacientes com seguro de saúde, a cobertura está sujeita às condições da respectiva apólice.</p>

                <h2 class="text-xl font-semibold text-gray-800 mt-6">6. Alterações aos Termos</h2>
                <p>Reservamo-nos o direito de modificar estes termos a qualquer momento. As alterações entrarão em vigor imediatamente após a sua publicação no portal.</p>
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