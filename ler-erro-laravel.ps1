# ====================================================================
# Script: ler-erro-laravel.ps1
# Descrição: Busca e exibe o último erro registrado no log do Laravel
# Uso: .\ler-erro-laravel.ps1
# ====================================================================

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  Diagnóstico de Erros Laravel" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan

$logFile = "storage\logs\laravel.log"

# Verifica se o arquivo de log existe
if (Test-Path $logFile) {
    # Busca a última linha que contém ".ERROR:" (funciona para local e production)
    $erro = Select-String -Path $logFile -Pattern "\.ERROR:" | Select-Object -Last 1
    
    if ($erro) {
        Write-Host "`n[ MENSAGEM DO ERRO ]" -ForegroundColor Yellow
        # Exibe a linha do erro formatada
        Write-Host $erro.Line -ForegroundColor Red
        
        Write-Host "`n[ INÍCIO DO RASTRO (ONDE O ERRO ACONTECEU) ]" -ForegroundColor Yellow
        # Pega as 15 linhas logo após a linha do erro para ver o topo do stack trace
        $indice = $erro.LineNumber
        Get-Content $logFile | Select-Object -Skip $indice -First 15 | ForEach-Object {
            Write-Host $_ -ForegroundColor Gray
        }
    } else {
        Write-Host "`nNenhum erro (.ERROR) encontrado no log. O sistema pode estar funcionando!" -ForegroundColor Green
    }
} else {
    Write-Host "`nArquivo de log não encontrado em: $logFile" -ForegroundColor Red
    Write-Host "Verifique se você está na raiz do projeto Laravel." -ForegroundColor Yellow
}

Write-Host "`n=========================================" -ForegroundColor Cyan