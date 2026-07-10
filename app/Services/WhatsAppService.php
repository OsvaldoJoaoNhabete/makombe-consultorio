<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Consultation;

class WhatsAppService
{
    protected $apiKey;
    protected $phoneNumberId;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.whatsapp.api_key', env('WHATSAPP_API_KEY'));
        $this->phoneNumberId = config('services.whatsapp.phone_number_id', env('WHATSAPP_PHONE_NUMBER_ID'));
        $this->baseUrl = 'https://graph.facebook.com/v18.0/' . $this->phoneNumberId;
    }

    /**
     * Enviar mensagem de agendamento de teleconsulta
     */
    public function sendConsultationMessage(Consultation $consultation): bool
    {
        $patient = $consultation->patient;
        $phoneNumber = $this->formatPhoneNumber($patient->phone);
        
        if (!$phoneNumber) {
            Log::warning('Número de telefone inválido para WhatsApp', [
                'patient_id' => $patient->id,
                'phone' => $patient->phone
            ]);
            return false;
        }

        $message = $this->buildConsultationMessage($consultation);

        // Se não tem API configurada, apenas loga
        if (!$this->apiKey || !$this->phoneNumberId) {
            Log::info('WhatsApp API não configurada. Mensagem gerada:', [
                'phone' => $phoneNumber,
                'message' => $message
            ]);
            return true;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/messages', [
                'messaging_product' => 'whatsapp',
                'to' => $phoneNumber,
                'type' => 'text',
                'text' => ['body' => $message]
            ]);

            if ($response->successful()) {
                Log::info('Mensagem WhatsApp enviada com sucesso', [
                    'patient_id' => $patient->id,
                    'consultation_id' => $consultation->id,
                    'phone' => $phoneNumber
                ]);
                return true;
            } else {
                Log::error('Erro ao enviar WhatsApp', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exceção ao enviar WhatsApp: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar mensagem personalizada
     */
    public function sendCustomMessage(string $phone, string $message): bool
    {
        $phoneNumber = $this->formatPhoneNumber($phone);
        
        if (!$phoneNumber) {
            Log::warning('Número de telefone inválido para WhatsApp', ['phone' => $phone]);
            return false;
        }

        // Se não tem API configurada, apenas loga
        if (!$this->apiKey || !$this->phoneNumberId) {
            Log::info('WhatsApp API não configurada. Mensagem gerada:', [
                'phone' => $phoneNumber,
                'message' => $message
            ]);
            return true;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/messages', [
                'messaging_product' => 'whatsapp',
                'to' => $phoneNumber,
                'type' => 'text',
                'text' => ['body' => $message]
            ]);

            if ($response->successful()) {
                Log::info('Mensagem WhatsApp personalizada enviada', [
                    'phone' => $phoneNumber
                ]);
                return true;
            } else {
                Log::error('Erro ao enviar WhatsApp', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exceção ao enviar WhatsApp: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Construir mensagem de teleconsulta formatada
     */
    protected function buildConsultationMessage(Consultation $consultation): string
    {
        $patient = $consultation->patient;
        $doctor = $consultation->doctor;
        $roomId = $consultation->getJitsiRoomId();
        
        $androidLink = 'https://play.google.com/store/apps/details?id=org.jitsi.meet';
        $iosLink = 'https://apps.apple.com/app/jitsi-meet/id1165103905';
        $desktopLink = 'https://github.com/jitsi/jitsi-meet-electron/releases';

        $message = "🏥 *MAKOMBE CONSULTÓRIO MÉDICO*\n";
        $message .= "\"Aqui você tem saúde\"\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "👤 *Paciente:* {$patient->full_name}\n";
        $message .= "👨‍⚕️ *Médico:* Dr(a). {$doctor->name}\n";
        $message .= "📅 *Data:* {$consultation->scheduled_at->format('d/m/Y')}\n";
        $message .= "⏰ *Hora:* {$consultation->scheduled_at->format('H:i')}\n";
        $message .= "💻 *Tipo:* Teleconsulta\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "🔗 *CREDENCIAIS DA REUNIÃO*\n\n";
        $message .= "📋 *ID da Sala:*\n`{$roomId}`\n\n";
        $message .= "🌐 *Link de Acesso:*\n{$consultation->location}\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "📱 *BAIXE O APLICATIVO JITSI MEET:*\n\n";
        $message .= "🤖 *Android:*\n{$androidLink}\n\n";
        $message .= "🍎 *iOS (iPhone):*\n{$iosLink}\n\n";
        $message .= "💻 *Desktop:*\n{$desktopLink}\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "⚠️ *IMPORTANTE:*\n";
        $message .= "• Chegue 5 minutos antes\n";
        $message .= "• Use fones de ouvido\n";
        $message .= "• Esteja em local silencioso\n";
        $message .= "• Tenha exames anteriores à mão\n\n";
        $message .= "❓ *Dúvidas?*\n";
        $message .= "📞 +258 84 123 4567\n";
        $message .= "📧 info@makombe.co.mz\n\n";
        $message .= "_Aguardamos você!_";

        return $message;
    }

    /**
     * Formatar número de telefone para WhatsApp
     */
    protected function formatPhoneNumber(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        
        if (str_starts_with($cleaned, '258') && strlen($cleaned) === 12) {
            return $cleaned;
        }
        
        if (strlen($cleaned) === 9) {
            return '258' . $cleaned;
        }
        
        if (strlen($cleaned) === 12) {
            return $cleaned;
        }
        
        return null;
    }

    /**
     * Gerar link wa.me para envio manual
     */
    public function generateWhatsAppLink(Consultation $consultation): string
    {
        $phone = $this->formatPhoneNumber($consultation->patient->phone);
        $message = urlencode($this->buildConsultationMessage($consultation));
        
        return "https://wa.me/{$phone}?text={$message}";
    }
}