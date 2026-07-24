<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Médica - {{ $consultation->patient->full_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Estilos Gerais */
        body {
            font-family: 'Roboto', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 40px;
            background: #fff;
        }

        /* Cabeçalho */
        .header {
            text-align: center;
            border-bottom: 2px solid #6d28d9; /* Cor Makombe */
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #6d28d9;
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        /* Informações do Paciente e Médico */
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
        }
        .info-box {
            width: 48%;
        }
        .info-box h3 {
            margin-top: 0;
            font-size: 14px;
            color: #6d28d9;
            text-transform: uppercase;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-box p {
            margin: 5px 0;
            font-size: 13px;
        }

        /* Conteúdo Clínico */
        .clinical-section {
            margin-bottom: 25px;
        }
        .clinical-section h2 {
            font-size: 16px;
            color: #333;
            border-left: 4px solid #6d28d9;
            padding-left: 10px;
            margin-bottom: 15px;
        }
        .clinical-content {
            font-size: 14px;
            white-space: pre-wrap; /* Mantém as quebras de linha do textarea */
            min-height: 100px;
        }

        /* Rodapé e Assinatura */
        .footer {
            margin-top: 80px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 250px;
            margin: 0 auto 10px auto;
            padding-top: 5px;
        }
        .signature-line p {
            margin: 0;
            font-size: 12px;
            font-weight: bold;
        }
        .signature-line span {
            font-size: 11px;
            font-weight: normal;
            color: #666;
        }

        /* Botões de Ação (Não aparecem na impressão) */
        .action-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            color: white;
        }
        .btn-print { background: #6d28d9; }
        .btn-back { background: #6b7280; }

        /* Regras de Impressão */
        @media print {
            body { padding: 20px; }
            .action-buttons { display: none !important; }
            .info-section { background: none; border: 1px solid #eee; }
            .header { border-bottom-color: #000; }
            .header h1 { color: #000; }
            .clinical-section h2 { border-left-color: #000; }
        }
    </style>
</head>
<body>

    <!-- Botões de Ação -->
    <div class="action-buttons">
        <a href="{{ route('consultations.show', $consultation->id) }}" class="btn btn-back">← Voltar</a>
        <button onclick="window.print()" class="btn btn-print">🖨️ Imprimir Nota</button>
    </div>

    <!-- Cabeçalho do Consultório -->
    <div class="header">
        <h1>Makombe Consultório Médico</h1>
        <p>"Aqui você tem saúde"</p>
        <p>📍 Endereço do Consultório, Maputo, Moçambique</p>
        <p>📞 +258 84 117 88 57 | ✉️ geral@makombe.co.mz</p>
    </div>

    <!-- Dados do Paciente e da Consulta -->
    <div class="info-section">
        <div class="info-box">
            <h3>Dados do Paciente</h3>
            <p><strong>Nome:</strong> {{ $consultation->patient->full_name }}</p>
            <p><strong>NID:</strong> {{ $consultation->patient->nid }}</p>
            <p><strong>Idade:</strong> {{ $consultation->patient->birth_date ? $consultation->patient->birth_date->age . ' anos' : 'N/D' }}</p>
            <p><strong>Género:</strong> {{ ucfirst($consultation->patient->gender ?? 'N/D') }}</p>
        </div>
        <div class="info-box">
            <h3>Dados da Consulta</h3>
            <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($consultation->scheduled_at)->format('d/m/Y H:i') }}</p>
            <p><strong>Médico:</strong> {{ $consultation->doctor->name }}</p>
            <p><strong>Especialidade:</strong> {{ $consultation->doctor->specialty->name ?? 'Clínica Geral' }}</p>
            <p><strong>Tipo:</strong> {{ ucfirst($consultation->type) }}</p>
        </div>
    </div>

    <!-- Prescrição Médica (Rx) -->
    <div class="clinical-section">
        <h2> Prescrição Médica</h2>
        <div class="clinical-content">
            {{ $consultation->prescription ?? 'Sem prescrição registada nesta consulta.' }}
        </div>
    </div>

    <!-- Exames e Diagnóstico -->
    <div class="clinical-section">
        <h2>🔬 Exames Solicitados e Diagnóstico</h2>
        <div class="clinical-content">
            {{ $consultation->clinical_notes ?? 'Sem exames ou diagnósticos registados.' }}
        </div>
    </div>

    <!-- Observações Adicionais -->
    @if($consultation->observations)
        <div class="clinical-section">
            <h2>📝 Observações</h2>
            <div class="clinical-content">
                {{ $consultation->observations }}
            </div>
        </div>
    @endif

    <!-- Rodapé com Assinatura -->
    <div class="footer">
        <div class="signature-line">
            <p>{{ $consultation->doctor->name }}</p>
            <span>{{ $consultation->doctor->specialty->name ?? 'Médico' }}</span>
        </div>
        <p style="margin-top: 40px; font-size: 10px; color: #999;">
            Documento gerado eletronicamente pelo sistema Makombe em {{ now()->format('d/m/Y H:i') }}.
        </p>
    </div>

</body>
</html>